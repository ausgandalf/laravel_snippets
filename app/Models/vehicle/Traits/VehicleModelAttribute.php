<?php

namespace App\Models\vehicle\Traits;

/**
 * Class VehicleModelAttribute.
 */
trait VehicleModelAttribute
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
                '.$this->getEditButtonAttribute("vehicle-edit", "biller.vehicles.models.edit").'
                '.$this->getDeleteButtonAttribute("vehicle-delete", "biller.vehicles.models.destroy",'table').'
                ';
    }
}
