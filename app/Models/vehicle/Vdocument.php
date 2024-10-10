<?php

namespace App\Models\vehicle;

use Illuminate\Database\Eloquent\Model;

class Vdocument extends Model
{
    /**
     * NOTE : If you want to implement Soft Deletes in this model,
     * then follow the steps here : https://laravel.com/docs/5.4/eloquent#soft-deleting
     */

    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'v_documents';

    /**
     * Mass Assignable fields of model
     * @var array
     */
    protected $fillable = [
        'vehicle_id',
        'document_description',
        'document_file',
        'created_by',
        'updated_by',

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
