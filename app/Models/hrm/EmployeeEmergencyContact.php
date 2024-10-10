<?php

namespace App\Models\hrm;

use Auth;
use App\Models\ModelTrait;
use Illuminate\Database\Eloquent\Model;
class EmployeeEmergencyContact extends Model
{
    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'employee_emergency_contacts';
    public $timestamps = false;
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
        
    }

}
