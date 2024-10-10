<?php

namespace App\Models\hrm\Traits;
use App\Models\hrm\Asset;
/**
 * Class AssetTypeRelationship
 */
trait AssetTypeRelationship
{
    public function assets()
    {
        return $this->hasMany(Asset::class, 'type_id','id');
    }
}
