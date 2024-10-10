<?php

namespace App\Models\hrm\Traits;
use App\Models\hrm\HrmMeta;
use App\Models\department\Department;
/**
 * Class PositionRelationship
 */
trait PositionRelationship
{
    public function employees()
    {
        return $this->hasMany(HrmMeta::class,'position_id','id');
    }
    public function department()
    {
        return $this->hasOne(Department::class,'id','dept_id');
    }
}
