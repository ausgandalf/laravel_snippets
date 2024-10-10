<?php

namespace App\Models\hrm\Traits;

use App\Models\hrm\EmployeeGrade;
use App\Models\hrm\LeaveAllocatedDay;
/**
 * Class LeaveTypeRelationship
 */
trait LeaveTypeRelationship
{
    
    public function allocated_days()
    {
        return $this->hasMany(LeaveAllocatedDay::class,'leave_type_id','id');
    }
}
