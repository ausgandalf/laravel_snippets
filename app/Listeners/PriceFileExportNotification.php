<?php
namespace App\Listeners;

use Config;
use App\Events\PriceFileExport;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\general\BackgroundJob;
use App\Models\product\PriceFile;
use App\Models\product\PriceFileMeta;
use App\Models\product\PriceFileDimension;
use App\Models\product\PriceFileImage;
use App\Models\productbrand\Productbrand;
use App\Models\productcategory\Productcategory;
use App\Models\supplier\Supplier;
use App\Models\additional\Additional;
use App\Models\general\Profile;

use Spatie\SimpleExcel\SimpleExcelWriter;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use File;
use Illuminate\Support\Facades\Log;

class PriceFileExportNotification implements ShouldQueue
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
     * @param  \App\Events\PriceFileExport  $event
     * @return void
     */
    public function handle(PriceFileExport $event)
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
        
        $q = PriceFile::where('ins', auth()->user()->ins);
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
            switch($conditions['price_type']){
                case '0':
                    if ($conditions['min_price'] != ""){
                        $q->where('price_1', '>=', $conditions['min_price']);
                    }
                    if ($conditions['max_price'] != ""){
                        $q->where('price_1', '<=', $conditions['max_price']);
                    }
                    break;
                case '1':
                    if ($conditions['min_price'] != ""){
                        $q->where('price_2', '>=', $conditions['min_price']);
                    }
                    if ($conditions['max_price'] != ""){
                        $q->where('price_2', '<=', $conditions['max_price']);
                    }
                    break;
                case '2':
                    if ($conditions['min_price'] != ""){
                        $q->where('price_3', '>=', $conditions['min_price']);
                    }
                    if ($conditions['max_price'] != ""){
                        $q->where('price_3', '<=', $conditions['max_price']);
                    }
                    break;
                case '3':
                    if ($conditions['min_price'] != ""){
                        $q->where('standard_cost', '>=', $conditions['min_price']);
                    }
                    if ($conditions['max_price'] != ""){
                        $q->where('standard_cost', '<=', $conditions['max_price']);
                    }
                    break;
                case '4':
                    if ($conditions['min_price'] != ""){
                        $q->where('fob_price', '>=', $conditions['min_price']);
                    }
                    if ($conditions['max_price'] != ""){
                        $q->where('fob_price', '<=', $conditions['max_price']);
                    }
                    break;
            }
        }
        $count = $q->count();
        $export_fields = $conditions['price_file_fields'];

        $i = 0;
        $filename = 'app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR .$path . "price_file_" . date("Y_m_d_his");
        
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
        $field_mapping_type = Config::get('constants.general.profile.price_file_export_field_mapping');
        $fields_mapping = Profile::where("type", $field_mapping_type)->first();
        $setting = [];
        $setting = json_decode($fields_mapping->setting, true);
        $export_headers = [];
        foreach ($export_fields as $key => $field){
            array_push($export_headers, $setting[$field]);
        }
        $writer->addHeader($export_headers);

        foreach ($q->lazy(1000) as $price_file){
            $price_info = [];
            $meta = PriceFileMeta::where("price_file_id", $price_file['id'])->first();
            $meta_arr = $meta->getAttributes();
            $dimension = PriceFileDimension::where("price_file_id", $price_file['id'])->first();
            $dimension_arr = $dimension->getAttributes();
            foreach ($export_fields as $key => $field){
                
                if ($field == "brand"){
                    $price_info['brand'] = $brands[$price_file->brand_id];
                }else if ($field == "part_number"){
                    $prefix = $prefixes[$price_file->brand_id];
                    $price_info['part_number'] = $price_file['part_number'];//preg_replace("/$prefix/", "", $price_file['part_number'], 1);
                }else if ($field == "name"){
                    $price_info['name'] = $price_file['name'];
                }else if ($field == "category"){
                    $price_info['category'] = $categories[$price_file['category_id']];
                }else if ($field == "sub_category"){
                    $price_info['sub_category'] = $subcategories[$price_file['sub_cat_id']];
                }else if ($field == "supplier"){
                    $price_info['supplier'] = $suppliers[$price_file['supplier_id']];
                }else if ($field == "stock_type"){
                    $price_info['stock_type'] = $price_file['stock_type'];
                }else if ($field == "unit"){
                    $price_info["unit"] = $price_file["unit"];
                }else if ($field == "sku"){
                    $price_info["sku"] = $price_file["sku"];
                }else if ($field == "superseding_part_no"){
                    $price_info["superseding_part_no"] = $price_file["superseding_part_no"];
                }else if ($field == "mpn"){
                    $price_info["mpn"] = $price_file["mpn"];
                }else if ($field == "retail_price"){
                    $price_info["retail_price"] = $price_file["price_1"];
                }else if ($field == "retail_price2"){
                    $price_info["retail_price2"] = $price_file["price_2"];
                }else if ($field == "warranty_price"){
                    $price_info["warranty_price"] = $price_file["price_3"];
                }else if ($field == "standard_cost"){
                    $price_info["standard_cost"] = $price_file["standard_cost"];
                }else if ($field == "fob_price"){
                    $price_info["fob_price"] = $price_file["fob_price"];
                }else if ($field == "default_tax_rate"){
                    $price_info[$field] = isset($tax_rates[$meta_arr[$field]])?$tax_rates[$meta_arr[$field]]:'';
                }else if (in_array($field, ['discount_code', 'barcode_type', 'barcode_value', 'country_of_origin', 'customs_code', 'tarrif_code', 'shelf_life']) === true){
                    $price_info[$field] = $meta_arr[$field];
                }else if (in_array($field, ['prod_start_date', 'price_change_date', 'expiry_date']) === true){
                    $price_info[$field] = dateFormat($meta_arr[$field]);
                }else if ($field == "main_image"){
                    if ($meta_arr['main_image']!=null){
                        $price_info['main_image'] = Storage::disk('public')->url('app/public/') . $meta_arr['main_image'];
                    }else{
                        $price_info['main_image'] = "";
                    }
                }else if (in_array($field, ['weight_unit', 'weight', 'length_unit', 'length', 'width', 'height']) === true){
                    $price_info[$field] = $dimension_arr[$field];
                }else if ($field == "additional_images"){
                    $additional_imgs = [];
                    foreach (PriceFileImage::where("price_file_id", $price_file['id'])->get() as $img){
                        array_push($additional_imgs, Storage::disk('public')->url('app/public/') . $img->additional_image);
                    }
                    $price_info['additional_images'] = implode("|", $additional_imgs);
                }
                
            }
            $writer->addRow($price_info);
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
    public function failed(PriceFileExport $event, $exception)
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