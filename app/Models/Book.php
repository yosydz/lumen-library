<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    use SoftDeletes;

    protected $fillable = [

        // TODO: Insert your fillable fields
        'title', 'description', 'author', 'year', 'synopsis', 'stock'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [

        // TODO: Insert your hidden fields
        'created_at', 'updated_at', 'deleted_at'
    ];
}
