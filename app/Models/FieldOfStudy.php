<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FieldOfStudy extends Model
{
    protected $table = 'fields_of_study';

    protected $fillable = [
        'name',
        'slug',
    ];
}