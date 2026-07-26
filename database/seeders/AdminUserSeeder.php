<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@boursepourtous.ci'],
            [
                'name' => 'Admin BPT',
                'password' => Hash::make('changeme123'), // à changer en prod !
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'redacteur@boursepourtous.ci'],
            [
                'name' => 'Rédacteur BPT',
                'password' => Hash::make('changeme123'),
                'role' => 'redacteur',
                'email_verified_at' => now(),
            ]
        );
    }
}