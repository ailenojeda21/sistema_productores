<?php

namespace Database\Factories;

use App\Models\Comercio;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ComercioFactory extends Factory
{
    protected $model = Comercio::class;

    public function definition(): array
    {
        return [
            'usuario_id' => User::factory(),
            'infraestructura_empaque' => fake()->boolean(),
            'vende_en_finca' => fake()->boolean(),
            'mercados' => fake()->randomElements(array_values(Comercio::MERCADOS), fake()->numberBetween(1, 3)),
            'cooperativas' => fake()->randomElements(array_values(Comercio::COOPERATIVAS), fake()->numberBetween(0, 2)),
        ];
    }
}
