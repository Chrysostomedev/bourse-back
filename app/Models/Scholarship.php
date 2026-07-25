<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Scholarship extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'organism_name',
        'organism_logo',
        'country_id',
        'scholarship_type_id',
        'funding_type',
        'objective',
        'conditions',
        'advantages',
        'additional_info',
        'official_link',
        'cover_image',
        'status',
        'is_featured',
        'views_count',
        'created_by',
    ];

    protected $casts = [
        'additional_info' => 'array',
        'is_featured' => 'boolean',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function scholarshipType(): BelongsTo
    {
        return $this->belongsTo(ScholarshipType::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}