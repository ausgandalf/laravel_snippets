<?php
namespace App\Listeners;

use Config;
use App\Events\ProductExport;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\general\BackgroundJob;
use App\Models\product\Product;
use App\Models\product\ProductVariation;
use App\Models\product\ProductMeta;
use App\Models\product\ProductDimension;
use App\Models\product\ProductImage;
use App\Models\product\ProductSpecial;
use App\Models\productbrand\Productbrand;
use App\Models\productcategory\Productcategory;
use App\Models\reordercategory\Reordercategory;
use App\Models\supplier\Supplier;
use App\Models\additional\Additional;
use App\Models\general\Profile;

use Spatie\SimpleExcel\SimpleExcelWriter;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use File;
use Illuminate\Support\Facades\Log;

class ProductExportNotification implements ShouldQueue
{
    // use InteractsWithQueue;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public $backgrond_job;
    public function __construct()
    {
        //
    }
 
    /**
     * Handle the event.
     *
     * @param  \App\Events\ProductExport  $event
     * @return void
     */
    public function handle(ProductExport $event)
    {
        
        $background_job = $event->background_job;
        
        $path = 'files' . DIRECTORY_SEPARATOR . 'company' . DIRECTORY_SEPARATOR;
        if (!file_exists(storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $path))){
            mkdir(storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $path));
        }
        $path .= auth()->user()->ins;
        if (!file_exists(storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $path))){
            mkdir(storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $path));
        }
        $path .= DIRECTORY_SEPARATOR . date("Y");
        if (!file_exists(storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $path))){
            mkdir(storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $path));
        }
        $path .= DIRECTORY_SEPARATOR . date("m");
        if (!file_exists(storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $path))){
            mkdir(storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $path));
        }
        $path .= DIRECTORY_SEPARATOR;
        $conditions = json_decode($background_job->conditions, true);
        
        $q = Product::where('ins', auth()->user()->ins);
        if (isset($conditions['brand_id'])){
            $q->whereIn('brand_id', $conditions['brand_id']);
        }
        if (isset($conditions['part_number'])){
            $q->where('part_number', 'like', '%'. $conditions['part_number'] . '%');
        }
        if (isset($conditions['category_id'])){
            $q->whereIn('category_id', $conditions['category_id']);
        }
        if (isset($conditions['supplier_id'])){
            $q->whereIn('supplier_id', $conditions['supplier_id']);
        }
        if (isset($conditions['price_type'])){
            $min_price = $conditions['min_price'];
            $max_price = $conditions['max_price'];
            switch($conditions['price_type']){
                case '0':
                    if ($conditions['min_price'] != ""){
                        $q->whereHas('standard', function ($v) use ($min_price) {
                            return $v->where('price', '>=', $min_price);
                        });
                    }
                    if ($conditions['max_price'] != ""){
                        $q->whereHas('standard', function ($v) use ($max_price) {
                            return $v->where('price', '<=', $max_price);
                        });
                    }
                    break;
                case '1':
                    if ($conditions['min_price'] != ""){
                        $q->whereHas('standard', function ($v) use ($min_price) {
                            return $v->where('price_2', '>=', $min_price);
                        });
                    }
                    if ($conditions['max_price'] != ""){
                        $q->whereHas('standard', function ($v) use ($max_price) {
                            return $v->where('price_2', '<=', $max_price);
                        });
                    }
                    break;
                case '2':
                    if ($conditions['min_price'] != ""){
                        $q->whereHas('standard', function ($v) use ($min_price) {
                            return $v->where('price_3', '>=', $min_price);
                        });
                    }
                    if ($conditions['max_price'] != ""){
                        $q->whereHas('standard', function ($v) use ($max_price) {
                            return $v->where('price_3', '<=', $max_price);
                        });
                    }
                    break;
                case '3':
                    if ($conditions['min_price'] != ""){
                        $q->whereHas('standard', function ($v) use($min_price) {
                            return $v->where('standard_cost', '>=', $min_price);
                        });
                    }
                    if ($conditions['max_price'] != ""){
                        $q->whereHas('standard', function ($v) use ($max_price){
                            return $v->where('standard_cost', '<=', $max_price);
                        });
                    }
                    break;
                case '4':
                    if ($conditions['min_price'] != ""){
                        $q->whereHas('standard', function ($v) use ($min_price) {
                            return $v->where('fob_price', '>=', $min_price);
                        });
                    }
                    if ($conditions['max_price'] != ""){
                        $q->whereHas('standard', function ($v) use ($max_price) {
                            return $v->where('fob_price', '<=', $max_price);
                        });
                    }
                    break;
            }
        }
        $count = $q->count();
        $export_fields = isset($conditions['product_fields'])?$conditions['product_fields']: [];

        $i = 0;
        if ($conditions['special_offer'] == "1"){
            $filename = 'app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR .$path . "product_special_offer_" . date("Y_m_d_his");
        }else{
            $filename = 'app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR .$path . "product_" . date("Y_m_d_his");
        }
        
        $brands = [];
        $brands[""] = "";
        $prefixes = [];
        foreach (Productbrand::All() as $brand){
            $brands[$brand->id] = $brand->name;
            $prefixes[$brand->id] = $brand->prefix;
        }
        $categories = [];
        $categories[""] = "";
        foreach (Productcategory::where("c_type", 0)->get() as $pcat){
            $categories[$pcat->id] = $pcat->title;
        }
        $subcategories = [];
        $subcategories[""] = "";
        foreach (Productcategory::where("c_type", 1)->get() as $pcat){
            $subcategories[$pcat->id] = $pcat->title;
        }
        $reordercategories = [];
        $reordercategories[""] = "";
        foreach (Reordercategory::all() as $rcat){
            $reordercategories[$rcat->id] = $rcat->name;
        }

        $suppliers = [];
        $suppliers[""] = "";
        foreach (Supplier::All() as $supplier){
            $suppliers[$supplier->id] = $supplier->name;
        }
        $stock_types = [trans('products.service'), trans('products.material'), trans('products.non_stocking_part')];
        $tax_rates = [];
        $tax_rates[""] = "";
        foreach (Additional::where("class", 1)->get() as $addition){
            $tax_rates[$addition->id] = $addition->name;
        }
        $background_jobs = Config::get('constants.general.background_jobs');
        $formats = Config::get('constants.general.export_formats');
        $compressions = Config::get('constants.general.compressions');
        
        $ext = strtolower($formats[$conditions['export_format']]);
        $writer = SimpleExcelWriter::create(storage_path($filename. "." . $ext));
        $field_mapping_type = Config::get('constants.general.profile.product_export_field_mapping');
        $fields_mapping = Profile::where("type", $field_mapping_type)->first();
        $setting = [];
        $setting = json_decode($fields_mapping->setting, true);
        $export_headers = [];
        if ($conditions['special_offer'] == "1"){
            array_push($export_headers, 
                trans('general.product_fields.part_number'),
                trans('general.product_fields.special_start_date_time'),
                trans('general.product_fields.special_end_date_time'),
                trans('general.product_fields.condition'),
                trans('general.product_fields.value')
            );
        }else{
            foreach ($export_fields as $key => $field){
                array_push($export_headers, $setting[$field]);
            }
        }
        $writer->addHeader($export_headers);
        $special_conditions = [
            trans('general.special_conditions.percentage'),
            trans('general.special_conditions.amount'),
            trans('general.special_conditions.fixed_price')
        ];
        foreach ($q->lazy(1000) as $product){
            if ($conditions['special_offer'] == "1"){
                $variation = ProductVariation::where("product_id", $product['id'])->where("parent_id", 0)->first();
                $variation_arr = $variation->getAttributes();
                $special_offers = ProductSpecial::where("product_id", $product['id'])->get();
                foreach ($special_offers as $so){
                    $product_info = [];
                    $product_info['part_number'] = $variation_arr['code'];
                    $product_info['special_start_date_time'] = dateFormat($so['start_date']) . " ". date("H:i", strtotime($so['start_time']));
                    $product_info['special_end_date_time'] = dateFormat($so['end_date']) . " " . date("H:i", strtotime($so['end_time']));
                    $product_info['condition'] = $special_conditions[$so['conditions']];
                    $product_info['value'] = $so['value'];
                    if ($so['conditions']){
                        $product_info['value'] .= '%';
                    }else{
                        $product_info['value'] = '$'.$product_info['value'];
                    }
                    $writer->addRow($product_info);
                }
                
            }else{
                $product_info = [];
                $variation = ProductVariation::where("product_id", $product['id'])->where("parent_id", 0)->first();
                $variation_arr = $variation->getAttributes();
                $dimension = ProductDimension::where("product_id", $product['id'])->first();
                
                if ($dimension){
                    $dimension_arr = $dimension->getAttributes();
                }else{
                    $dimension_arr = [];
                }
                $serials = ProductMeta::where('ref_id', $product['id'])->where('rel_type', 2)->where('rel_id', 0)->select("value")->get();
                $serial_arr = [];
                foreach ($serials as $serial){
                    array_push($serial_arr, $serial->value);
                }
                foreach ($export_fields as $key => $field){
                
                    if ($field == "brand"){
                        $product_info['brand'] = $brands[$product->brand_id];
                    }else if ($field == "part_number"){
                        $prefix = $prefixes[$product->brand_id];
                        $product_info['part_number'] = $variation_arr['code'];//preg_replace("/$prefix/", "", $product['part_number'], 1);
                    }else if ($field == "name"){
                        $product_info['name'] = $product['name'];
                    }else if ($field == "product_des"){
                        $product_info['description'] = $product['product_des'];
                    }else if ($field == "category"){
                        $product_info['category'] = $categories[$product['productcategory_id']];
                    }else if ($field == "sub_category"){
                        $product_info['sub_category'] = $subcategories[$product['sub_cat_id']];
                    }else if ($field == "supplier"){
                        $product_info['supplier'] = $suppliers[$product['supplier_id']];
                    }else if ($field == "stock_type"){
                        $product_info['stock_type'] = $stock_types[$product['stock_type']];
                    }else if ($field == "default_tax_rate"){
                        $product_info[$field] = isset($tax_rates[$variation_arr[$field]])?$tax_rates[$variation_arr[$field]]:'';
                    }else if ($field == "unit"){
                        $product_info["unit"] = $product["unit"];
                    }else if ($field == "discount_code"){
                        $product_info["discount_code"] = $variation_arr["discount_code"];
                    }else if ($field == "sku"){
                        $product_info["sku"] = $product["sku"];
                    }else if ($field == "reorder_category"){
                        $product_info["reorder_category"] = $product["reorder_category_id"] > 0?$reordercategories[$product["reorder_category_id"]]:"";
                    }else if ($field == "superseding_part_no"){
                        $product_info["superseding_part_no"] = $product["superseding_part_no"];
                    }else if ($field == "prevent_price_update"){
                        $product_info["prevent_price_update"] = $product["superseding_part_no"]==0?"No":"Yes";
                    }else if ($field == "mpn"){
                        $product_info["mpn"] = $product["mpn"];
                    }else if ($field == "product_serial"){
                        $product_info["product_serial"] = implode("|", $serial_arr);
                    }else if ($field == "barcode_type"){
                        $product_info["barcode_type"] = $product["code_type"];
                    }else if ($field == "barcode_value"){
                        $product_info["barcode_value"] = $variation_arr["barcode"];
                    }else if (in_array($field, ['country_of_origin', 'customs_code', 'tarrif_code', 'shelf_life']) === true){
                        $product_info[$field] = $variation_arr[$field];
                    }else if (in_array($field, ['weight_unit', 'weight', 'length_unit', 'length', 'width', 'height']) === true){
                        $product_info[$field] = isset($dimension_arr[$field])?$dimension_arr[$field]:'';
                    }else if ($field == "price_change_date"){
                        $product_info[$field] = dateFormat($variation_arr[$field]);
                    }else if ($field == "expiry_date"){
                        $product_info[$field] = dateFormat($variation_arr["expiry"]);
                    }else if ($field == "last_change_date_time"){
                        $product_info["last_change_date_time"] = dateFormat($variation_arr['last_change_date']) . ' ' . date("H:i", strtotime($variation_arr['last_change_time']));
                    }else if ($field == "qty"){
                        $product_info["qty"] = $variation_arr['qty'];
                    }else if ($field == "retail_price"){
                        $product_info["retail_price"] = $variation_arr["price"];
                    }else if ($field == "wholesale_price"){
                        $product_info["wholesale_price"] = $variation_arr["price_2"];
                    }else if ($field == "warranty_price"){
                        $product_info["warranty_price"] = $variation_arr["price_3"];
                    }else if ($field == "standard_cost"){
                        $product_info["standard_cost"] = $variation_arr["standard_cost"];
                    }else if ($field == "fob_price"){
                        $product_info["fob_price"] = $variation_arr["fob_price"];
                    }else if ($field == "special_offer"){
                        $product_info["special_offer"] = "";
                    }else if ($field == "main_image"){
                        if ($variation_arr['image']!=null){
                            $product_info['main_image'] = Storage::disk('public')->url('app/public/') . $variation_arr['image'];
                        }else{
                            $product_info['main_image'] = "";
                        }
                    }else if ($field == "additional_images"){
                        $additional_imgs = [];
                        foreach (ProductImage::where("product_id", $product['id'])->get() as $img){
                            array_push($additional_imgs, Storage::disk('public')->url('app/public/') . $img->additional_image);
                        }
                        $product_info['additional_images'] = implode("|", $additional_imgs);
                    }
                    
                }
                $writer->addRow($product_info);
            }
            
            $i++;
            if ($i % 1000 === 0) {
                $background_job->percent = $i * 100 / $count;
                $background_job->update(
                [
                    'percent' => $background_job->percent,
                    'total' => $count,
                    'processed_cnt'=> $i,
                    'status'=>$background_jobs['STATUS']['PROCESSING']
                ]
                );

                flush(); // Flush the buffer every 1000 rows
            }
            
        }
        if ($ext!= "csv"){
            $writer->close();
        }
        
        if ( $conditions['compression'] == array_search('zipped', $compressions) ){
            $zip = new ZipArchive;
            Log::info(storage_path($filename. ".zip"));
            // Log::info($zip->open(storage_path($filename. ".zip"), ZipArchive::CREATE));
            if ($zip->open(storage_path($filename. ".zip"), ZipArchive::CREATE) === TRUE)
            {
                $file = basename(storage_path($filename. "." . $ext));
                $zip->addFile(storage_path($filename. "." . $ext), $file);
                $zip->close();
                $background_job->percent = 100;
                $background_job->update(
                    [
                        'percent' => $background_job->percent,
                        'total' => $count,
                        'processed_cnt'=> $count,
                        'path'=>$filename.".zip",
                        'status'=>$background_jobs['STATUS']['FINISHED']
                    ]
                );
            }else{
                $background_job->percent = 100;
                $background_job->update(
                [
                    'percent' => $background_job->percent,
                    'total' => $count,
                    'processed_cnt'=> $count,
                    'path'=>$filename. "." . $ext,
                    'status'=>$background_jobs['STATUS']['FINISHED']
                ]
                );
            }
        }else{
            $background_job->percent = 100;
            $background_job->update(
            [
                'percent' => $background_job->percent,
                'total' => $count,
                'processed_cnt'=> $count,
                'path'=>$filename. "." . $ext,
                'status'=>$background_jobs['STATUS']['FINISHED']
            ]
            );
        }
        
        
    }
    public function failed(ProductExport $event, $exception)
    {
        $background_job = $event->background_job;
        $background_jobs = Config::get('constants.general.background_jobs');
        $background_job->update(
            [
                'status'=>$background_jobs['STATUS']['FAILED']
            ]
        );
    }
}