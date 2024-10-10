<?php

namespace App\Models\productbrand\Traits;

/**
 * Class ProductbrandAttribute.
 */
trait ProductbrandAttribute
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
         '.$this->getViewButtonAttribute("productbrand-manage", "biller.productbrands.show").'
                '.$this->getEditButtonAttribute("productbrand-data", "biller.productbrands.edit").'
                '.$this->getDeleteButtonAttribute("productbrand-data", "biller.productbrands.destroy").'
                ';
    }
}
