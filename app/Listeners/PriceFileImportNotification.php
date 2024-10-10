<?php
namespace App\Listeners;
 
use DB;
use ZipArchive;
use File;

use App\Events\PriceFileImport;
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

use Spatie\SimpleExcel\SimpleExcelReader;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Log;

class PriceFileImportNotification implements ShouldQueue
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
     * @param  \App\Events\PriceFileImport  $event
     * @return void
     */
    public function handle(PriceFileImport $event)
    {
        $background_job = $event->background_job;
        
        $zip = new ZipArchive;
        $filename = $background_job->path;
        
        if ($zip->open(storage_path($filename)) === TRUE){
            $info = pathinfo($filename);
            $zip->extractTo(storage_path($info['dirname']));
            $csv_filename = $zip->getNameIndex(0);
            $zip->close();
            $background_job->path = $info['dirname'] . '/' . $csv_filename;
            $background_job->update(
                [
                    'path'=>$info['dirname'] . '/' . $csv_filename
                ]
                );

            $reader = SimpleExcelReader::create(storage_path($background_job->path))->useDelimiter(',')->getRows();
            $meta = [];
            $meta['total'] = $reader->count();
            $meta['processed_cnt'] = 0;
            $meta['invalid_cnt'] = 0;
            
            $meta['chunk_step'] = 5000;

            $meta['brands'] = [];
            $meta["brands"][""] = "";
            $meta["prefixes"] = [];
            foreach (Productbrand::All() as $brand){
                $meta["brands"][$brand->id] = $brand->name;
                $meta["prefixes"][$brand->id] = $brand->prefix;
            }

            $categories = [];
            $categories[""] = "";
            foreach (Productcategory::where("c_type", 0)->get() as $pcat){
                $categories[$pcat->id] = $pcat->title;
            }
            $meta['categories'] = $categories;
            
            $suppliers = [];
            $suppliers[""] = "";
            foreach (Supplier::All() as $supplier){
                $suppliers[$supplier->id] = $supplier->name;
            }
            $meta['suppliers'] = $suppliers;
            $meta['stock_types'] = [trans('products.service'), trans('products.material'), trans('products.non_stocking_part')];
            $tax_rates = [];
            $tax_rates[""] = "";
            foreach (Additional::where("class", 1)->get() as $addition){
                $tax_rates[$addition->id] = $addition->name;
            }
            $meta['tax_rates'] = $tax_rates;
            SimpleExcelReader::create(storage_path($background_job->path))->getRows()->chunk($meta['chunk_step'])->each(
            function($rows) use(&$background_job, &$meta){
                foreach ($rows as $row){
                    DB::beginTransaction();
                    try{
                        $input['main'] = [];
                        $input["main"]["brand_id"] = array_search($row["brand"], $meta['brands']);
                        if ($input["main"]["brand_id"] === false){
                            DB::rollback();
                            $meta['invalid_cnt']++;
                            continue;
                        }
                        $input["main"]["part_number"] = $meta["prefixes"][$input["main"]["brand_id"]] . $row['part_number'];
                        $input["main"]["name"] = $row["name"];
                        $input["main"]["category_id"] = array_search($row["category"], $meta['categories']);
                        if ($input["main"]["category_id"] === false){
                            DB::rollback();
                            $meta['invalid_cnt']++;
                            continue;
                        }
                        $subcategories = [];
                        $subcategories[""] = "";
                        foreach (Productcategory::where("c_type", 1)->where("rel_id", $input["main"]["category_id"])->get() as $pcat){
                            $subcategories[$pcat->id] = $pcat->title;
                        }
                        $input["main"]["sub_cat_id"] = array_search($row["sub_category"], $subcategories);
                        if ($input["main"]["sub_cat_id"] === false){
                            DB::rollback();
                            $meta['invalid_cnt']++;
                            continue;
                        }
                        $input["main"]["supplier_id"] = array_search($row["supplier"], $meta['suppliers']);
                        if ($input["main"]["supplier_id"] === false){
                            DB::rollback();
                            $meta['invalid_cnt']++;
                            continue;
                        }
                        $input["main"]["stock_type"] = array_search($row["stock_type"], $meta['stock_types']);
                        if ($input["main"]["stock_type"] === false){
                            DB::rollback();
                            $meta['invalid_cnt']++;
                            continue;
                        }
                        
                        $input["main"]["unit"] = $row["unit"];
                        $input["main"]["sku"] = $row["sku"];
                        $input["main"]["superseding_part_no"] = $row["superseding_part_no"];
                        $input["main"]["mpn"] = $row["mpn"];
                        $input["main"]["price_1"] = $row["retail_price"];
                        $input["main"]["price_2"] = $row["retail_price2"];
                        $input["main"]["price_3"] = $row["warranty_price"];
                        $input["main"]["standard_cost"] = $row["standard_cost"];
                        $input["main"]["fob_price"] = $row["fob_price"];
                        $result = PriceFile::create($input['main']);
                        $input['meta'] = [];
                        $input["main"]["default_tax_rate"] = array_search($row["default_tax_rate"], $meta['tax_rates']);
                        if ($input["main"]["default_tax_rate"] === false){
                            DB::rollback();
                            $meta['invalid_cnt']++;
                            continue;
                        }
                        $input['meta']['price_file_id'] = $result->id;
                        $input['meta']['discount_code'] = $row['discount_code'];
                        $input['meta']['barcode_type'] = $row['barcode_type'];
                        $input['meta']['barcode_value'] = $row['barcode_value'];
                        $input['meta']['country_of_origin'] = strtolower($row['country_of_origin']);
                        $input['meta']['customs_code'] = $row['customs_code'];
                        $input['meta']['tarrif_code'] = $row['tarrif_code'];
                        $input['meta']['prod_start_date'] = date_for_database($row['prod_start_date']);
                        $input['meta']['price_change_date'] = date_for_database($row['price_change_date']);
                        $input['meta']['shelf_life'] = $row['shelf_life'];
                        $input['meta']['expiry_date'] = date_for_database($row['expiry_date']);
                        $input['meta']['main_image'] = $row['main_image'];
                        PriceFileMeta::create($input['meta']);

                        $input['dimension'] = [];
                        $input['dimension']['price_file_id'] = $result->id;
                        $input['meta']['weight_unit'] = $row['weight_unit'];
                        $input['meta']['weight'] = $row['weight'];
                        $input['meta']['length_unit'] = $row['length_unit'];
                        $input['meta']['length'] = $row['length'];
                        $input['meta']['width'] = $row['width'];
                        $input['meta']['height'] = $row['height'];
                        PriceFileDimension::create($input['dimension']);

                        $input['images'] = explode("|", $row['additional_images']);
                        if (!empty($input['images'])){
                            foreach ($input['images'] as $img){
                                PriceFileImage::create(['price_file_id'=>$result->id, 'additional_image'=>$img]);
                            }
                        }
                    }catch (QueryException $e){
                        DB::rollback();
                        $meta['invalid_cnt']++;
                        continue;
                    }
                    DB::commit();
                }
                
                $meta['processed_cnt']+=count($rows);
                $background_job->update(
                    [
                        'percent'=> $meta['processed_cnt'] / $meta['total'] * 100
                    ]
                );  
            });
            
            $background_job->update(
                [
                    'percent'=>100,
                    'conditions'=>json_encode(['total'=>$meta['total'], 'processed_cnt'=>$meta['processed_cnt'], 'invalid_cnt'=>$meta['invalid_cnt']])
                ]
            );  
            

        }
        
    }
    public function failed(PriceImport $event, $exception)
    {
        //
    }
}