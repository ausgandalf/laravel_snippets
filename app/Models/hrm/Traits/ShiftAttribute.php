<?php

namespace App\Models\hrm\Traits;
use Illuminate\Database\Eloquent\Casts\Attribute;
/**
 * Class ShiftAttribute.
 */
trait ShiftAttribute
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
                '.$this->getViewButtonAttribute("manage-hrm", "biller.hrms.shifts.show") . '
                '.$this->getEditButtonAttribute("manage-hrm", "biller.hrms.shifts.edit").'
                '.$this->getDeleteButtonAttribute("manage-hrm", "biller.hrms.shifts.destroy",'table').'
                ';
    }

    
    /**
     * @return Attribute
     */
    protected function closingTime(): Attribute
    {
        return Attribute::make(
            get: fn($value) => date("g:i A", strtotime($value))
        );
    }
    /**
     * @return Attribute
     */
    protected function openingTime(): Attribute
    {
        return Attribute::make(
            get: fn($value) => date("g:i A", strtotime($value))
        );
    }
}
