<?php

namespace Database\Factories;

use App\Models\Maquinaria;
use App\Models\Propiedad;
use Illuminate\Database\Eloquent\Factories\Factory;

class MaquinariaFactory extends Factory
{
    protected $model = Maquinaria::class;

    public function definition(): array
    {
        return [
            'propiedad_id' => Propiedad::factory(),
            'tractor' => fake()->boolean(),
            'modelo_tractor' => fake()->optional()->numberBetween(1990, (int) date('Y')),
            'arado' => fake()->boolean(),
            'rastra' => fake()->boolean(),
            'niveleta_comun' => fake()->boolean(),
            'niveleta_laser' => fake()->boolean(),
            'cincel_cultivadora' => fake()->boolean(),
            'desmalezadora' => fake()->boolean(),
            'pulverizadora_tractor' => fake()->boolean(),
            'mochila_pulverizadora' => fake()->boolean(),
            'cosechadora' => fake()->boolean(),
            'enfardadora' => fake()->boolean(),
            'retroexcavadora' => fake()->boolean(),
            'carro_carreton' => fake()->boolean(),
            'multiple' => fake()->boolean(),
        ];
    }
}
