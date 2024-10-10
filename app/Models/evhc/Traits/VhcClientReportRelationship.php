<?php

namespace App\Models\evhc\Traits;
use App\Models\evhc\VhcClientReportMeta;
/**
 * Class VhcClientReportRelationship
 */
trait VhcClientReportRelationship
{
    public function meta()
    {
        return $this->hasMany(VhcVhcClientReportMeta::class, 'vcr_id','id');
    }
}
