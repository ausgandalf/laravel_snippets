<?php

namespace App\Models\hrm\Traits;

/**
 * Class HolidayAttribute.
 */
trait HolidayAttribute
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
         ' . $this->getViewButtonAttribute("department-manage", "biller.hrms.holiday_show") . '
                ' . $this->getEditButtonAttribute("department-manage", "biller.hrms.holiday_edit") . '
                ' . $this->getDeleteButtonAttribute("department-manage", "biller.hrms.holiday_destroy", 'table') . '
                ';
    }

}
