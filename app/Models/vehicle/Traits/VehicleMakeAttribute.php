<?php

namespace App\Models\vehicle\Traits;

/**
 * Class VehicleMakeAttribute.
 */
trait VehicleMakeAttribute
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
                '.$this->getEditButtonAttribute("vehicle-edit", "biller.vehicles.makes.edit").'
                '.$this->getDeleteButtonAttribute("vehicle-delete", "biller.vehicles.makes.destroy",'table').'
                ';
    }
}
