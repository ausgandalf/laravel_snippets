<?php

namespace App\Models\workshop\Traits;

/**
 * Class IdletimeAttribute.
 */
trait IdletimeAttribute
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
                '.$this->getEditButtonAttribute("workshop-edit", "biller.workshops.idletimes.edit").'
                '.$this->getDeleteButtonAttribute("workshop-delete", "biller.workshops.idletimes.destroy",'table').'
                ';
    }
}
