<?php

namespace App\Models\evhc\Traits;

/**
 * Class VhcAttribute.
 */
trait VhcAttribute
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
                '.$this->getViewButtonAttribute("workshop-manage", "biller.evhcs.evhc.show").'
                '.$this->getEditButtonAttribute("workshop-edit", "biller.evhcs.evhc.edit").'
                '.$this->getDeleteButtonAttribute("workshop-delete", "biller.evhcs.evhc.destroy",'table').'
                ';
    }
}
