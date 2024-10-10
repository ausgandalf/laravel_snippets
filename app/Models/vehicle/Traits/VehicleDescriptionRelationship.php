<?php

namespace App\Models\vehicle\Traits;
use App\Models\vehicle\Vservice;
/**
 * Class VehicleDescriptionRelationship
 */
trait VehicleDescriptionRelationship
{
    public function services()
    {
        return $this->hasMany(Vservice::class, 'd_id','id');
    }
}
