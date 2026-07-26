<?php

namespace App\Services;

use App\Models\SavedScholarship;
use App\Models\Scholarship;
use App\Models\User;

class SavedScholarshipService
{
    public function toggle(Scholarship $scholarship, User $user): bool
    {
        $existing = SavedScholarship::query()
            ->where('user_id', $user->id)
            ->where('scholarship_id', $scholarship->id)
            ->first();

        if ($existing) {
            $existing->delete();

            return false; // n'est plus en favoris
        }

        SavedScholarship::create([
            'user_id' => $user->id,
            'scholarship_id' => $scholarship->id,
        ]);

        return true; // ajoutée aux favoris
    }
}