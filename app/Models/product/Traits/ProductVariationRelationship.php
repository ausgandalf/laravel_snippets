<?php

namespace App\Models\product\Traits;
use App\Models\product\Product;
use App\Models\product\ProductMeta;
use App\Models\warehouse\Warehouse;
use App\Models\product\ProductSpecial;

/**
 * Class ProductRelationship
 */
trait ProductVariationRelationship
{

    public function warehouse()
    {
        return $this->hasOne(Warehouse::class,'id','warehouse_id');
    }

        public function product_serial()
    {
        return $this->hasMany(ProductMeta::class, 'ref_id', 'id')->where('rel_type','=',2)->withoutGlobalScopes();
    }


        public function product()
    {
        return $this->belongsTo(Product::class);
    }

         public function category()
    {
        return $this->hasOneThrough(Productcategory::class,Product::class,'product_id','productcategory_id');
    }
    public function specials()
    {
        return $this->hasMany(ProductSpecial::class);
    }




}
