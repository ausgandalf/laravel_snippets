<?php

namespace App\Models\vehicle\Traits;

/**
 * Class VehiclePartAttribute.
 */
trait VehiclePartAttribute
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
                '.$this->getEditButtonAttribute("vehicle-edit", "biller.vehicles.parts.edit").'
                '.$this->getDeleteButtonAttribute("vehicle-delete", "biller.vehicles.parts.destroy",'table').'
                ';
    }
}
