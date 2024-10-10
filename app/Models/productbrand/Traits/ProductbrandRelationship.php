<?php

namespace App\Models\productbrand\Traits;

use App\Models\product\Product;
use App\Models\product\ProductVariation;
use App\Models\supplier\Supplier;
use DB;
/**
 * Class ProductbrandRelationship
 */
trait ProductbrandRelationship
{
    public function supplier()
    {
        return $this->hasOne(Supplier::class,'id','default_supplier');
    }
}
