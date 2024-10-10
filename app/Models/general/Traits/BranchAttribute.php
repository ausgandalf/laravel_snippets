<?php

namespace App\Models\general\Traits;

/**
 * Class BranchAttribute.
 */
trait BranchAttribute
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
                '.$this->getEditButtonAttribute("business_settings", "biller.branch_settings.edit").'
                '.$this->getDeleteButtonAttribute("business_settings", "biller.branch_settings.destroy",'table').'
                ';
    }
}
