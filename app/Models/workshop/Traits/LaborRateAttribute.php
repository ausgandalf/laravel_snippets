<?php

namespace App\Models\workshop\Traits;

/**
 * Class LaborRateAttribute.
 */
trait LaborRateAttribute
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
                '.$this->getEditButtonAttribute("workshop-edit", "biller.workshops.labor_rates.edit").'
                '.$this->getDeleteButtonAttribute("workshop-delete", "biller.workshops.labor_rates.destroy",'table').'
                ';
    }
}
