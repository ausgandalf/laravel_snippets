<?php

namespace App\Models\vehicle;

use App\Models\ModelTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\vehicle\Traits\VehicleModelAttribute;
use App\Models\vehicle\Traits\VehicleModelRelationship;

class Vmodel extends Model
{
    use ModelTrait,
        VehicleModelAttribute,
    	VehicleModelRelationship {
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
    protected $table = 'v_models';

    /**
     * Mass Assignable fields of model
     * @var array
     */
    protected $fillable = [
        'make_id',
        'name',
        'ins'
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
        static::addGlobalScope('ins', function ($builder) {
            $builder->where('ins', '=', auth()->user()->ins);
        });
    }

}
