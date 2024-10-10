<?php

namespace App\Models\evhc\Traits;

/**
 * Class VhcClientReportAttribute.
 */
trait VhcClientReportAttribute
{
    // Make your attributes functions here
    // Further, see the documentation : https://laravel.com/docs/5.4/eloquent-mutators#defining-an-accessor


    /**
     * Action Button Attribute to show in grid
     * @return string
     */
    public function getActionButtonsAttribute()
    {
        return '';
    }
}
