<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Ordre important : les référentiels et l'admin doivent exister
        // avant DemoScholarshipSeeder, qui les recherche par nom.
        $this->call([
            CountrySeeder::class,
            StudyLevelSeeder::class,
            ScholarshipTypeSeeder::class,
            FieldOfStudySeeder::class,
            AdminUserSeeder::class,
            DemoScholarshipSeeder::class,
        ]);
    }
}