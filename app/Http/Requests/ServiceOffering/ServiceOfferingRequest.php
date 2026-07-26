<?php

namespace App\Http\Requests\ServiceOffering;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Nommé "ServiceOfferingRequest" (et non "ServiceRequest") pour éviter
 * toute confusion avec le namespace App\Services\* — mais il valide
 * bien le modèle App\Models\Service (coaching/formation/dossier).
 */
class ServiceOfferingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:150'],
            'kind' => ['required', 'in:coaching,formation,dossier'],
            'description' => ['required', 'string'],
            'price' => ['required', 'integer', 'min:0'], // FCFA, 0 = gratuit
            'image' => ['nullable', 'image', 'max:2048'],
            'is_active' => ['boolean'],
        ];
    }
}