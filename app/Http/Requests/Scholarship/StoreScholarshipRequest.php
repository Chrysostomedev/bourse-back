<?php

namespace App\Http\Requests\Scholarship;

use Illuminate\Foundation\Http\FormRequest;

class StoreScholarshipRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:180'],

            'organism_name' => ['required', 'string', 'max:150'],
            'organism_logo' => ['nullable', 'image', 'max:2048'], // 2 Mo max

            'country_id' => ['nullable', 'exists:countries,id'],
            'scholarship_type_id' => ['nullable', 'exists:scholarship_types,id'],

            'funding_type' => ['required', 'in:partielle,totale'],

            'objective' => ['required', 'string'],
            'conditions' => ['required', 'string'],
            'advantages' => ['required', 'string'],

            // Liste de conseils/points, ex: ["Renouvellement possible", "TOEFL requis"]
            'additional_info' => ['nullable', 'array'],
            'additional_info.*' => ['string', 'max:500'],

            'official_link' => ['nullable', 'url', 'max:255'],
            'cover_image' => ['nullable', 'image', 'max:4096'], // 4 Mo max

            'status' => ['required', 'in:brouillon,publie,archive'],
            'is_featured' => ['boolean'],

            // --- Relations many-to-many : tableaux d'ids ---
            'study_level_ids' => ['required', 'array', 'min:1'],
            'study_level_ids.*' => ['exists:study_levels,id'],

            'field_of_study_ids' => ['required', 'array', 'min:1'],
            'field_of_study_ids.*' => ['exists:fields_of_study,id'],

            // --- Périodes de candidature (au moins une) ---
            'intakes' => ['required', 'array', 'min:1'],
            'intakes.*.intake_label' => ['required', 'string', 'max:150'],
            'intakes.*.period_start' => ['nullable', 'date'],
            'intakes.*.period_end' => ['nullable', 'date', 'after_or_equal:intakes.*.period_start'],
            'intakes.*.period_label_text' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'study_level_ids.required' => 'Sélectionne au moins un niveau d\'étude.',
            'field_of_study_ids.required' => 'Sélectionne au moins une filière.',
            'intakes.required' => 'Ajoute au moins une période de candidature.',
        ];
    }
}