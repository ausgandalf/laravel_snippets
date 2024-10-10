<?php

namespace App\Models\warehouse\Traits;

use App\Models\product\Product;
use App\Models\product\ProductVariation;
use App\Models\warehouse\WarehouseZone;
use App\Models\general\Branch;
use DB;
/**
 * Class WarehouseRelationship
 */
trait WarehouseRelationship
{
    public function products()
    {
        return $this->hasMany(ProductVariation::class)->select([DB::raw('qty*price as total_value'),'qty']);
    }
    public function zones()
    {
        return $this->hasMany(WarehouseZone::class, 'w_id','id');
    }
    public function branch()
    {
        return $this->hasOne(Branch::class, 'id','branch_id');
    }
}
