<?php

namespace App\Models\hrm\Traits;
use App\Models\hrm\Hrm;
use App\Models\hrm\LeaveType;

/**
 * Class LeaveRequestRelationship
 */
trait LeaveRequestRelationship
{
    public function employee()
    {
        return $this->hasOne(Hrm::class, 'id','employee_id');
    }
    public function leave_type()
    {
        return $this->hasOne(LeaveType::class, 'id','leave_type_id');
    }
}
