<?php

namespace App\Models\general;

use App\Models\ModelTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\general\Traits\VehicleColorAttribute;
use App\Models\general\Traits\VehicleColorRelationship;
class Color extends Model
{
    use ModelTrait,
        VehicleColorAttribute,
    	VehicleColorRelationship {
            // VehicleAttribute::getEditButtonAttribute insteadof ModelTrait;
        }
    /**
     * NOTE : If you want to implement Soft Deletes in this model,
     * then follow the steps here : https://laravel.com/docs/5.4/eloquent#soft-deleting
     */

    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'colors';
    public $timestamps = false;

    /**
     * Mass Assignable fields of model
     * @var array
     */
    protected $fillable = [
        'name',
        'value'
    ];

    /**
     * Constructor of Model
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
    protected static function boot()
    {
        parent::boot();
        
    }

}
