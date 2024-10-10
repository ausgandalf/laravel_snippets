<?php

namespace App\Models\hrm\Traits;
use App\Models\hrm\HolidayType;
/**
 * Class HolidayRelationship
 */
trait HolidayRelationship
{
    public function type()
    {
        return $this->hasOne(HolidayType::class,'id','type_id');
    }
}
