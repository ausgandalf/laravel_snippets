<?php

namespace App\Models\hrm\Traits;
/**
 * Class AssetTypeAttribute.
 */
trait AssetTypeAttribute
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
                '.$this->getEditButtonAttribute("manage-hrm", "biller.hrms.asset_types.edit").'
                '.$this->getDeleteButtonAttribute("manage-hrm", "biller.hrms.asset_types.destroy",'table').'
                ';
    }
    
}
