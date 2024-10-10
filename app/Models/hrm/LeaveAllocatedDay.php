<?php

namespace App\Models\hrm;

use Illuminate\Database\Eloquent\Model;
use App\Models\hrm\EmployeeGrade;
use Illuminate\Database\Eloquent\Casts\Attribute;

class LeaveAllocatedDay extends Model
{
    
    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'leave_allocated_days';
    public $timestamps = false;

    /**
     * Mass Assignable fields of model
     * @var array
     */
    protected $fillable = [
        'leave_type_id',
        'grade_id',
        'allocated_days',
        'calculation_method'
    ];

    public function getEmployeeGradesAttribute()
    {
        $grade_ids = explode(",", $this->grade_id);
        $grade_str = "";
        foreach ($grade_ids as $grade_id){
            if (is_numeric($grade_id)){
                $grade_str .= EmployeeGrade::find($grade_id)->employee_grade . ", ";
            }
        }
        if (strlen($grade_str) > 0){
            $grade_str = substr($grade_str, 0, -2);
        }
        return $grade_str;
        //return $this->hasOne(EmployeeGrade::class,'id','grade_id');
    }

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
