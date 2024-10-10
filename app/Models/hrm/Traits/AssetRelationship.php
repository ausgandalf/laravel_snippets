<?php

namespace App\Models\hrm\Traits;
use App\Models\hrm\AssetType;
use App\Models\User;
/**
 * Class AssetRelationship
 */
trait AssetRelationship
{
    public function asset_type()
    {
        return $this->hasOne(AssetType::class, 'id','type_id');
    }
    public function assigned_user()
    {
        return $this->hasOne(User::class, 'id','assigned_to');
    }
}
