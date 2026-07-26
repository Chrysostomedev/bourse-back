<?php

namespace App\Http\Requests\Like;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ToggleLikeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'likeable_type' => ['required', Rule::in(['scholarship', 'post'])],
            'likeable_id' => ['required', 'integer'],
        ];
    }
}