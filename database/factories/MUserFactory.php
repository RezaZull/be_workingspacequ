<?php

namespace Database\Factories;

use Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MUser>
 */
class MUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName,
            'last_name' => fake()->lastName,
            'username' => fake()->userName,
            'email' => fake()->email,
            'password' => Hash::make('admin123'),
            'id_m_roles' => rand(1, 2),
            'obj_type' => 5,
            'created_by' => "SYSTEM",
            'img_path' => "storage/images/profile/images.webp",
            'flag_active' => true,
        ];
    }
}
