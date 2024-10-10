<?php

namespace App\Models\workshop;

use App\Models\ModelTrait;
use Illuminate\Database\Eloquent\Model;
class TeamSlot extends Model
{
    /**
     * NOTE : If you want to implement Soft Deletes in this model,
     * then follow the steps here : https://laravel.com/docs/5.4/eloquent#soft-deleting
     */

    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'workshop_loading_team_slots';
    public $timestamps = false;
    /**
     * Mass Assignable fields of model
     * @var array
     */
    protected $fillable = [
        'team_id',
        'slot_time'
    ];
    /**
     * Default values for model fields
     * @var array
     */
    protected $attributes = [
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
