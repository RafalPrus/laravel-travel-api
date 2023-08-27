<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Travel>
 */
class TravelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->text(20),
            'is_public' => fake()->boolean(),
            'description' => fake()->text(100),
            'number_of_days' => rand(1, 10),
        ];

//        $table->id();
//        $table->boolean('is_public')->default(false);
//        $table->string('slug')->unique();
//        $table->string('name');
//        $table->text('description');
//        $table->unsignedInteger('number_of_days');
//        $table->timestamps();
    }
}
