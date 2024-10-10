<?php

namespace App\Models\vehicle\Traits;

use App\Models\vehicle\DamageFile;

/**
 * Class DamageInfoRelationship
 */
trait DamageInfoRelationship
{
    public function files()
    {
        return $this->hasMany(DamageFile::class, 'd_id');
    }

}
