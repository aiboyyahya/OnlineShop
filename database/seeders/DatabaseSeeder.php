<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::updateOrCreate([
            'email' => 'bimboahmad78@gmail.com',
        ], [
            'name' => 'Jaka',
            'role' => 'admin',
             'password' => Hash::make('password123'),
        ]);
    }
}
