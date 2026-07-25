<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FieldOfStudy extends Model
{
    protected $fillable = [
        'name',
        'slug',
    ];
}