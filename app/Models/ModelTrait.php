<?php

namespace App\Models;

trait ModelTrait
{
    /**
     * @return string
     */
    public function getEditButtonAttribute($permission, $route)
    {
        if (access()->allow($permission)) {
            return '<a href="'.route($route, $this).'" class="link-edit protip" data-pt-position="top" data-pt-title="'.trans('general.edit').'" data-placement="top" title="Edit">
                    <i  class="fa fa-edit "></i>
                </a>';
        }
    }

       /**
     * @return string
     */
    public function getViewButtonAttribute($permission, $route)
    {
        if (access()->allow($permission)) {
            return '<a href="'.route($route, $this).'" class="link-view protip" data-pt-position="top" data-pt-title="'.trans('general.view').'" title="View">
                    <i  class="fa fa-eye"></i>
                </a>';
        }
    }

    /**
     * @return string
     */
    public function getDeleteButtonAttribute($permission, $route,$d_type='data')
    {
        if (access()->allow($permission)) {
            return '<a href="'.route($route, $this).'" 
                    class="link-delete protip" '.$d_type.'-method="delete"
                    data-pt-position="top" data-pt-title="'.trans('general.delete').'" 
                    data-trans-button-cancel="'.trans('buttons.general.cancel').'"
                    data-trans-button-confirm="'.trans('buttons.general.crud.delete').'"
                    data-trans-title="'.trans('strings.backend.general.are_you_sure').'" data-toggle="tooltip" data-placement="top" title="Delete">
                    <i class="fa-solid fa-trash"></i>
                </a>';
        }
    }
}
