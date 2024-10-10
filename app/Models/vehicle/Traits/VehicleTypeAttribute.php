<?php

namespace App\Models\vehicle\Traits;

/**
 * Class VehicleTypeAttribute.
 */
trait VehicleTypeAttribute
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
                '.$this->getEditButtonAttribute("vehicle-edit", "biller.vehicles.types.edit").'
                '.$this->getDeleteButtonAttribute("vehicle-delete", "biller.vehicles.types.destroy",'table').'
                ';
    }
}
