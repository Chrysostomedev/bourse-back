<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            ['name' => 'Canada', 'code_iso2' => 'CA', 'flag_emoji' => '🇨🇦'],
            ['name' => 'France', 'code_iso2' => 'FR', 'flag_emoji' => '🇫🇷'],
            ['name' => 'Royaume-Uni', 'code_iso2' => 'GB', 'flag_emoji' => '🇬🇧'],
            ['name' => 'Allemagne', 'code_iso2' => 'DE', 'flag_emoji' => '🇩🇪'],
            ['name' => 'États-Unis', 'code_iso2' => 'US', 'flag_emoji' => '🇺🇸'],
            ['name' => 'Belgique', 'code_iso2' => 'BE', 'flag_emoji' => '🇧🇪'],
            ['name' => 'Maroc', 'code_iso2' => 'MA', 'flag_emoji' => '🇲🇦'],
            ['name' => 'Côte d\'Ivoire', 'code_iso2' => 'CI', 'flag_emoji' => '🇨🇮'],
             ['name' => 'Japon', 'code_iso2' => 'JA', 'flag_emoji' => 'ja'],
            ['name' => 'Chine', 'code_iso2' => 'CH', 'flag_emoji' => 'ch'],
        ];

        foreach ($countries as $country) {
            Country::updateOrCreate(['code_iso2' => $country['code_iso2']], $country);
        }
    }
}