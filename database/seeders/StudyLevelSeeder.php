<?php

namespace Database\Seeders;

use App\Models\StudyLevel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StudyLevelSeeder extends Seeder
{
    public function run(): void
    {
        $levels = ['Licence', 'Master', 'Doctorat', 'Post-graduate'];

        foreach ($levels as $name) {
            StudyLevel::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
        }
    }
}