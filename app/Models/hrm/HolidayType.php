<?php

namespace App\Models\hrm;

use App\Models\ModelTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\hrm\Traits\HolidayTypeAttribute;
use App\Models\hrm\Traits\HolidayTypeRelationship;
class HolidayType extends Model
{
    use ModelTrait,
        HolidayTypeAttribute,
    	HolidayTypeRelationship {
            // HolidayTypeAttribute::getEditButtonAttribute insteadof ModelTrait;
        }
    /**
     * NOTE : If you want to implement Soft Deletes in this model,
     * then follow the steps here : https://laravel.com/docs/5.4/eloquent#soft-deleting
     */

    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'holiday_types';

    /**
     * Mass Assignable fields of model
     * @var array
     */
    protected $fillable = [
        
    ];
    /**
     * Default values for model fields
     * @var array
     */
    protected $attributes = [
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
            $builder->where('ins', '=', auth()->user()->ins);
        });
    }

}
