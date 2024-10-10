<?php

namespace App\Models\hrm\Traits;
/**
 * Class LeaveTypeAttribute.
 */
trait LeaveTypeAttribute
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
                '.$this->getEditButtonAttribute("manage-hrm", "biller.hrms.leave_types.edit").'
                '.$this->getDeleteButtonAttribute("manage-hrm", "biller.hrms.leave_types.destroy",'table').'
                ';
    }

}
