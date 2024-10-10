<?php

namespace App\Models\product\Traits;
use App\Models\product\ProductVariation;
use App\Models\product\ProductDimension;
use App\Models\product\ProductImage;
use App\Models\productcategory\Productcategory;
use App\Models\productbrand\Productbrand;
use App\Models\warehouse\Warehouse;
use App\Models\supplier\Supplier;
use App\Models\reordercategory\Reordercategory;
/**
 * Class ProductRelationship
 */
trait ProductRelationship
{

    public function standard()
    {
        return $this->hasOne(ProductVariation::class)->where('parent_id', 0);
    }

    public function variations()
    {
        return $this->hasMany(ProductVariation::class)->where('parent_id', 1);
    }

    public function variations_b()
    {
        return $this->belongsTo(ProductVariation::class)->where('parent_id', 1);
    }

     public function category()
    {
        return $this->hasOne(Productcategory::class,'id','productcategory_id');
    }
    
    public function subcategory()
    {
        return $this->hasOne(Productcategory::class,'id','sub_cat_id');
    }
    public function reordercategory()
    {
        return $this->hasOne(Reordercategory::class,'id','reorder_category_id');
    }
    public function supplier()
    {
        return $this->hasOne(Supplier::class,'id','supplier_id');
    }
    public function brand()
    {
        return $this->hasOne(Productbrand::class,'id','brand_id');
    }
    public function dimension()
    {
        return $this->hasOne(ProductDimension::class,'product_id','id');
    }
    public function images()
    {
        return $this->hasMany(ProductImage::class,'product_id','id');
    }
     public function record()
    {
        return $this->hasMany(ProductVariation::class);
    }
    public function record_one()
    {
        return $this->hasOne(ProductVariation::class);
    }


}
