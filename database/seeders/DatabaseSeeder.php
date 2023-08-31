<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Tour;
use App\Models\Travel;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $travel = Travel::factory()->create();
        Tour::factory(10)->create(['travel_id' => $travel->id]);

        $this->call(RoleSeeder::class);
    }
}
