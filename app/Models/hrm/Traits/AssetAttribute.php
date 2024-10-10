<?php

namespace App\Models\hrm\Traits;
/**
 * Class AssetAttribute.
 */
trait AssetAttribute
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
        ' . $this->getViewButtonAttribute("manage-hrm", "biller.hrms.assets.show") . '
                '.$this->getEditButtonAttribute("manage-hrm", "biller.hrms.assets.edit").'
                '.$this->getDeleteButtonAttribute("manage-hrm", "biller.hrms.assets.destroy",'table').'
                ';
    }
    
}
