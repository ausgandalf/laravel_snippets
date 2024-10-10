<?php

namespace App\Models\product\Traits;

/**
 * Class PriceFileAttribute.
 */
trait PriceFileAttribute
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
                '.$this->getEditButtonAttribute("product-edit", "biller.products.price_files.edit").'
                '.$this->getDeleteButtonAttribute("product-delete", "biller.products.price_files.destroy",'table').'
                ';
    }
}
