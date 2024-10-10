<?php

namespace App\Models\hrm\Traits;

/**
 * Class HolidayTypeAttribute.
 */
trait HolidayTypeAttribute
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
                '.$this->getEditButtonAttribute("department-manage", "biller.hrms.holiday_types.edit").'
                '.$this->getDeleteButtonAttribute("department-manage", "biller.hrms.holiday_types.destroy",'table').'
                ';
    }
}
