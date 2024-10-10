<?php

namespace App\Models\damagereport\Traits;

/**
 * Class DamageTypeAttribute.
 */
trait DamageTypeAttribute
{
    // Make your attributes functions here
    // Further, see the documentation : https://laravel.com/docs/5.4/eloquent-mutators#defining-an-accessor


    /**
     * Action Button Attribute to show in grid
     * @return string
     */
    public function getActionButtonsAttribute()
    {
        return '
                '.$this->getEditButtonAttribute("damage-report-edit", "biller.damagereports.damagetypes.edit").'
                '.$this->getDeleteButtonAttribute("damage-report-delete", "biller.damagereports.damagetypes.destroy",'table').'
                ';
    }
}
