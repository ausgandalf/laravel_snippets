<?php

namespace App\Models\workshop\Traits;

/**
 * Class ServiceCodeAttribute.
 */
trait ServiceCodeAttribute
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
                '.$this->getEditButtonAttribute("workshop-edit", "biller.workshops.service_codes.edit").'
                '.$this->getDeleteButtonAttribute("workshop-delete", "biller.workshops.service_codes.destroy",'table').'
                ';
    }
}
