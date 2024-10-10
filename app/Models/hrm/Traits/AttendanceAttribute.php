<?php

namespace App\Models\hrm\Traits;

/**
 * Class AttendanceAttribute.
 */
trait AttendanceAttribute
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
                '.$this->getEditButtonAttribute("manage-hrm", "biller.hrms.attendance_edit").'
                '.$this->getDeleteButtonAttribute("manage-hrm", "biller.hrms.attendance_destroy",'table').'
                ';
    }
}
