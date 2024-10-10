<?php

namespace App\Models\evhc\Traits;

/**
 * Class ItemAttribute.
 */
trait ItemAttribute
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
                '.$this->getEditButtonAttribute("workshop-edit", "biller.evhcs.items.edit").'
                '.$this->getDeleteButtonAttribute("workshop-delete", "biller.evhcs.items.destroy",'table').'
                ';
    }
}
