<?php

namespace App\Models\hrm\Traits;

/**
 * Class EmployeeGradeAttribute.
 */
trait EmployeeGradeAttribute
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
                '.$this->getEditButtonAttribute("manage-hrm", "biller.hrms.employee_grades.edit").'
                '.$this->getDeleteButtonAttribute("manage-hrm", "biller.hrms.employee_grades.destroy",'table').'
                ';
    }
}
