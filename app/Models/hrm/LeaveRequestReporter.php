<?php

namespace App\Models\hrm;

use Illuminate\Database\Eloquent\Model;

class LeaveRequestReporter extends Model
{
    
    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'leave_request_reporters';
    public $timestamps = false;

    /**
     * Mass Assignable fields of model
     * @var array
     */
    protected $fillable = [
        'request_id',
        'employee_id'
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
