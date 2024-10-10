<?php

namespace App\Models\hrm\Traits;
/**
 * Class LeaveRequestAttribute.
 */
trait LeaveRequestAttribute
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
                '.$this->getViewButtonAttribute("manage-hrm", "biller.hrms.leave_requests.show").'
                '.$this->getEditButtonAttribute("manage-hrm", "biller.hrms.leave_requests.edit").'
                '.$this->getDeleteButtonAttribute("manage-hrm", "biller.hrms.leave_requests.destroy",'table').'
                ';
    }

}
