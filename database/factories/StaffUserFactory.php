<?php

namespace Database\Factories;

use App\Models\StaffUser;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StaffUserFactory extends Factory
{
    protected $model = StaffUser::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'role' => 'admin',
            'active' => true,
            'remember_token' => Str::random(10),
        ];
    }

    public function auditor(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'auditor',
        ]);
    }
}
