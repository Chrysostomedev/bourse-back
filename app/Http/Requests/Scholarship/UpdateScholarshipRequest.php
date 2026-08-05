<?php

namespace App\Http\Requests\Scholarship;

use Illuminate\Foundation\Http\FormRequest;

class UpdateScholarshipRequest extends FormRequest
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
            // Sur update, on ne renvoie un fichier QUE si l'utilisateur
            // veut remplacer l'image existante — sinon on garde l'ancienne
            // (voir ScholarshipService::update()).
            'organism_logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'], // 5 Mo max

            'country_id' => ['nullable', 'exists:countries,id'],
            'scholarship_type_id' => ['nullable', 'exists:scholarship_types,id'],

            'funding_type' => ['required', 'in:partielle,totale'],

            'objective' => ['required', 'string'],
            'conditions' => ['required', 'string'],
            'advantages' => ['required', 'string'],

            'additional_info' => ['nullable', 'array'],
            'additional_info.*' => ['string', 'max:500'],

            'official_link' => ['nullable', 'url', 'max:255'],
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],

            'status' => ['required', 'in:brouillon,publie,archive'],
            'is_featured' => ['boolean'],

            'study_level_ids' => ['required', 'array', 'min:1'],
            'study_level_ids.*' => ['exists:study_levels,id'],

            'field_of_study_ids' => ['required', 'array', 'min:1'],
            'field_of_study_ids.*' => ['exists:fields_of_study,id'],

            // Sur update, on remplace TOUTES les périodes existantes par
            // celles envoyées ici (voir ScholarshipService::syncIntakes) —
            // plus simple à raisonner côté front qu'un diff partiel.
            'intakes' => ['required', 'array', 'min:1'],
            'intakes.*.intake_label' => ['required', 'string', 'max:150'],
            'intakes.*.period_start' => ['nullable', 'date'],
            'intakes.*.period_end' => ['nullable', 'date', 'after_or_equal:intakes.*.period_start'],
            'intakes.*.period_label_text' => ['nullable', 'string', 'max:100'],
        ];
    }
}