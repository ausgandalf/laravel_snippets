<?php

namespace App\Models\productbrand;

use App\Models\ModelTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\productbrand\Traits\ProductbrandAttribute;
use App\Models\productbrand\Traits\ProductbrandRelationship;

class Productbrand extends Model
{
    use ModelTrait,
        ProductbrandAttribute,
    	ProductbrandRelationship {
            // ProductbrandAttribute::getEditButtonAttribute insteadof ModelTrait;
        }

    /**
     * NOTE : If you want to implement Soft Deletes in this model,
     * then follow the steps here : https://laravel.com/docs/5.4/eloquent#soft-deleting
     */

    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'product_brands';

    /**
     * Mass Assignable fields of model
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'img_url',
        'prefix',
        'default_supplier',
        'analysis_code',
        'price1_name',
        'price1_currency',
        'price1_factor',
        'price2_name',
        'price2_currency',
        'price2_factor',
        'price3_name',
        'price3_currency',
        'price3_factor',
        'nl_posting_code',
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
            static::addGlobalScope('ins', function($builder){
            $builder->where('ins', '=', auth()->user()->ins);
    });
    }
}
