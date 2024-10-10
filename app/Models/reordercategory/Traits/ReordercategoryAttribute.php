<?php

namespace App\Models\reordercategory\Traits;

/**
 * Class ReordercategoryAttribute.
 */
trait ReordercategoryAttribute
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
         '.$this->getEditButtonAttribute("productcategory-data", "biller.reordercategories.edit").'
                '.$this->getDeleteButtonAttribute("productcategory-data", "biller.reordercategories.destroy").'
                ';
    }
}
