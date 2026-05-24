<?php

namespace Database\Factories;

use App\Models\Cultivo;
use App\Models\Propiedad;
use Illuminate\Database\Eloquent\Factories\Factory;

class CultivoFactory extends Factory
{
    protected $model = Cultivo::class;

    public function definition(): array
    {
        $tipo = fake()->randomElement(['Hortícola', 'Vitícola', 'Olivícola', 'Frutícola']);
        $variedades = Cultivo::getVariedadesForTipo($tipo);

        return [
            'propiedad_id' => Propiedad::factory(),
            'tipo' => $tipo,
            'variedad' => fake()->randomElement(array_keys($variedades)),
            'estacion' => fake()->randomElement(['Verano', 'Invierno', 'Otoño', 'Primavera']),
            'hectareas' => fake()->randomFloat(2, 0.5, 30),
            'manejo_cultivo' => fake()->randomElement(['Convencional', 'Agroecologico', 'Organico']),
            'tecnologia_riego' => fake()->randomElement(['Surco', 'Goteo', 'Aspersión', 'Inundación']),
        ];
    }
}
