<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::query()
            ->create([
                'name' => fake()->name,
                'email' => 'admin@alab.pl',
                'password' => Hash::make('Alab123!')
            ]);
    }
}
