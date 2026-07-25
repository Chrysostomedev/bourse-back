<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'title',
        'category',
        'description',
        'price',
        'cover_image',
        'file_url',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}