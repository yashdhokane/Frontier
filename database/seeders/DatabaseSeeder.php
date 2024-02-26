<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(10)->create();

        \App\Models\Role::factory()->create(['name' => 'admin',]);
        \App\Models\Role::factory()->create(['name' => 'technician',]);
        \App\Models\Role::factory()->create(['name' => 'user',]);

        // \App\Models\Technician::factory(10)->create();
    }
}
