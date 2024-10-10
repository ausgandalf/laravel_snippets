<?php

namespace App\Models\evhc\Traits;

/**
 * Class SectionAttribute.
 */
trait SectionAttribute
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
                '.$this->getEditButtonAttribute("workshop-edit", "biller.evhcs.sections.edit").'
                '.$this->getDeleteButtonAttribute("workshop-delete", "biller.evhcs.sections.destroy",'table').'
                ';
    }
}
