<?php

namespace App\Models\workshop\Traits;
use App\Models\hrm\Hrm;
/**
 * Class ResourceRelationship
 */
trait ResourceRelationship
{
    public function employee()
    {
        return $this->hasOne(Hrm::class, 'id','employee_id');
    }
}
