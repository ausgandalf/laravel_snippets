<?php

namespace App\Models\workshop;

use App\Models\ModelTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\workshop\Traits\IdletimeAttribute;
use App\Models\workshop\Traits\IdletimeRelationship;
class Idletime extends Model
{
    use ModelTrait,
        IdletimeAttribute,
    	IdletimeRelationship {
        }
    /**
     * NOTE : If you want to implement Soft Deletes in this model,
     * then follow the steps here : https://laravel.com/docs/5.4/eloquent#soft-deleting
     */

    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'workshop_loading_idletimes';

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
            if (auth()->user()==null){
                return redirect('login');
            }
            $builder->where('ins', '=', auth()->user()->ins);
        });
        static::creating(function ($model) {
            $model->ins = auth()->user()->ins;
            $model->created_by = auth()->user()->id;
            $model->updated_by = auth()->user()->id;
        });

        static::updating(function ($model) {
            $model->ins = auth()->user()->ins;
            $model->updated_by = auth()->user()->id;
        });
    }

}
