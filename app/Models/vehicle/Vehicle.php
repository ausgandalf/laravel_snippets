<?php

namespace App\Models\vehicle;

use App\Models\ModelTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\vehicle\Traits\VehicleAttribute;
use App\Models\vehicle\Traits\VehicleRelationship;

class Vehicle extends Model
{
    use ModelTrait,
        VehicleAttribute,
    	VehicleRelationship {
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
    protected $table = 'vehicles';

    /**
     * Mass Assignable fields of model
     * @var array
     */
    protected $fillable = [
        'owner_id',
        'driver_id',
        'reg_number',
        'reg_date',
        'vin',
        'security_code',
        'engine_num',
        'last_mileage',
        'odometer_type',
        'v_type_id',
        'v_description_id',
        'v_description',
        'model_year',
        'ext_color_id',
        'int_color_id',
        'v_status',
        'manu_date',
        'country_origin_id',
        'driver_side',
        'fuel_type',
        'transmission',
        'body',
        'radio_code',
        'engine_capacity',
        'axles',
        'gross_vehicle_weight',
        'tare_weight',
        'front_axle_group_rate',
        'rear_axle_group_rate',
        'ins',
        'created_by',
        'updated_by'
    ];

    /**
     * Default values for model fields
     * @var array
     */
    protected $attributes = [

    ];

    /**
     * Dates
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    /**
     * Guarded fields of model
     * @var array
     */
    protected $guarded = [
        'id'
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
            if (auth()->user()==null){
                return redirect('login');
            }
            $builder->where('ins', '=', auth()->user()->ins);
        });
    }

}
