<?php

namespace Database\Seeders;

use App\Models\FieldOfStudy;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FieldOfStudySeeder extends Seeder
{
    public function run(): void
    {
        $fields = [
            'Toutes filières',
            'Informatique',
            'Ingénierie',
            'Droit',
            'Économie',
            'Sciences politiques',
            'Agronomie',
            'Sciences de l\'environnement',
            'Sciences sociales',
             'Sciences juridiques',
              'Sciences medicales',
               'Langues',
        ];

        foreach ($fields as $name) {
            FieldOfStudy::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
        }
    }
}