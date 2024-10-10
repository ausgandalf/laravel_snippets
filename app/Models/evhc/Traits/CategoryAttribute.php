<?php

namespace App\Models\evhc\Traits;

/**
 * Class CategoryAttribute.
 */
trait CategoryAttribute
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
                '.$this->getEditButtonAttribute("workshop-edit", "biller.evhcs.categories.edit").'
                '.$this->getDeleteButtonAttribute("workshop-delete", "biller.evhcs.categories.destroy",'table').'
                ';
    }
}
