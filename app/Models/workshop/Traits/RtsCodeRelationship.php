<?php

namespace App\Models\workshop\Traits;
use App\Models\workshop\RtsCodeSkill;
use App\Models\workshop\RtsCodeModelAllowedTime;
/**
 * Class RtsCodeRelationship
 */
trait RtsCodeRelationship
{
    public function skills()
    {
        return $this->hasMany(RtsCodeSkill::class, 'rts_code_id','id');
    }
    public function model_allowed_times()
    {
        return $this->hasMany(RtsCodeModelAllowedTime::class, 'rts_code_id','id');
    }
}
