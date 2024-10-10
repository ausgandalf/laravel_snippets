<?php

namespace App\Models\evhc\Traits;
use App\Models\evhc\VhcMeta;
/**
 * Class VhcRelationship
 */
trait VhcRelationship
{
    public function meta()
    {
        return $this->hasMany(VhcMeta::class, 'vhc_id','id')->orderBy("sort_order", "asc");
    }
}
