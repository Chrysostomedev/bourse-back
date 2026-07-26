<?php

namespace App\Services;

use App\Models\Scholarship;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ScholarshipService
{
    public function create(array $data, int $authorId): Scholarship
    {
        $scholarship = Scholarship::create([
            'title' => $data['title'],
            'slug' => $this->uniqueSlug($data['title']),
            'organism_name' => $data['organism_name'],
            'organism_logo' => $this->storeImage($data['organism_logo'] ?? null, 'scholarships/logos'),
            'country_id' => $data['country_id'] ?? null,
            'scholarship_type_id' => $data['scholarship_type_id'] ?? null,
            'funding_type' => $data['funding_type'],
            'objective' => $data['objective'],
            'conditions' => $data['conditions'],
            'advantages' => $data['advantages'],
            'additional_info' => $data['additional_info'] ?? [],
            'official_link' => $data['official_link'] ?? null,
            'cover_image' => $this->storeImage($data['cover_image'] ?? null, 'scholarships/covers'),
            'status' => $data['status'],
            'is_featured' => $data['is_featured'] ?? false,
            'created_by' => $authorId,
        ]);

        $this->syncRelations($scholarship, $data);

        return $scholarship->fresh(['country', 'scholarshipType', 'studyLevels', 'fieldsOfStudy', 'intakes']);
    }

    public function update(Scholarship $scholarship, array $data): Scholarship
    {
        $scholarship->update([
            'title' => $data['title'],
            // On ne régénère le slug que si le titre a changé, pour ne
            // pas casser un lien déjà partagé pour rien.
            'slug' => $scholarship->title === $data['title']
                ? $scholarship->slug
                : $this->uniqueSlug($data['title'], $scholarship->id),
            'organism_name' => $data['organism_name'],
            'organism_logo' => isset($data['organism_logo'])
                ? $this->storeImage($data['organism_logo'], 'scholarships/logos', $scholarship->organism_logo)
                : $scholarship->organism_logo,
            'country_id' => $data['country_id'] ?? null,
            'scholarship_type_id' => $data['scholarship_type_id'] ?? null,
            'funding_type' => $data['funding_type'],
            'objective' => $data['objective'],
            'conditions' => $data['conditions'],
            'advantages' => $data['advantages'],
            'additional_info' => $data['additional_info'] ?? [],
            'official_link' => $data['official_link'] ?? null,
            'cover_image' => isset($data['cover_image'])
                ? $this->storeImage($data['cover_image'], 'scholarships/covers', $scholarship->cover_image)
                : $scholarship->cover_image,
            'status' => $data['status'],
            'is_featured' => $data['is_featured'] ?? false,
        ]);

        $this->syncRelations($scholarship, $data);

        return $scholarship->fresh(['country', 'scholarshipType', 'studyLevels', 'fieldsOfStudy', 'intakes']);
    }

    public function publish(Scholarship $scholarship): Scholarship
    {
        $scholarship->update(['status' => 'publie']);

        return $scholarship;
    }

    public function archive(Scholarship $scholarship): Scholarship
    {
        $scholarship->update(['status' => 'archive']);

        return $scholarship;
    }

    public function delete(Scholarship $scholarship): void
    {
        // softDeletes activé sur la migration : la bourse disparaît des
        // listes publiques mais reste en base (utile pour les stats
        // historiques et un éventuel undo côté admin).
        $scholarship->delete();
    }

    /**
     * Remplace intégralement les niveaux, filières et périodes de
     * candidature — plus simple et plus sûr qu'un diff partiel, et le
     * front envoie de toute façon la liste complète à chaque fois.
     */
    private function syncRelations(Scholarship $scholarship, array $data): void
    {
        $scholarship->studyLevels()->sync($data['study_level_ids']);
        $scholarship->fieldsOfStudy()->sync($data['field_of_study_ids']);

        $scholarship->intakes()->delete();
        foreach ($data['intakes'] as $intake) {
            $scholarship->intakes()->create([
                'intake_label' => $intake['intake_label'],
                'period_start' => $intake['period_start'] ?? null,
                'period_end' => $intake['period_end'] ?? null,
                'period_label_text' => $intake['period_label_text'] ?? null,
            ]);
        }
    }

    /**
     * Génère un slug unique à partir du titre. En cas de collision
     * (deux bourses au même nom, ex: éditions différentes), on ajoute
     * un suffixe numérique incrémental.
     */
    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;

        while (
            Scholarship::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    /**
     * Stocke une image sur le disque public et retourne son chemin
     * relatif. Si un ancien fichier est fourni, il est supprimé pour
     * ne pas accumuler des images orphelines dans le storage.
     */
    private function storeImage(?UploadedFile $file, string $folder, ?string $previousPath = null): ?string
    {
        if (! $file) {
            return $previousPath;
        }

        if ($previousPath) {
            Storage::disk('public')->delete($previousPath);
        }

        return $file->store($folder, 'public');
    }
}