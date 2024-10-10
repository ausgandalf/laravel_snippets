<?php

namespace App\Models\vehicle\Traits;

/**
 * Class VehicleAttribute.
 */
trait VehicleAttribute
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
         '.$this->getViewButtonAttribute("vehicle-manage", "biller.vehicles.vehicle.show").'
                '.$this->getEditButtonAttribute("vehicle-edit", "biller.vehicles.vehicle.edit").'
                '.$this->getDeleteButtonAttribute("vehicle-delete", "biller.vehicles.vehicle.destroy",'table').'
                ';
    }
}
