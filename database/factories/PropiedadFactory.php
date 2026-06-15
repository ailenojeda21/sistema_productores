<?php

namespace Database\Factories;

use App\Models\Propiedad;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PropiedadFactory extends Factory
{
    protected $model = Propiedad::class;

    public function definition(): array
    {
        $distritos = array_keys(Propiedad::DISTRITOS);

        return [
            'usuario_id' => User::factory(),
            'calle' => fake()->streetName(),
            'numeracion' => (string) fake()->numberBetween(100, 9999),
            'distrito' => fake()->randomElement($distritos),
            'hectareas' => fake()->randomFloat(2, 1, 100),
            'derecho_riego' => fake()->boolean(),
            'tipo_derecho_riego' => fake()->randomElement(['Subterráneo', 'Superficial', 'Ambos']),
            'rut' => fake()->boolean(),
            'rut_valor' => fake()->optional()->numerify('########'),
            'tipo_tenencia' => fake()->randomElement(['propietario', 'arrendatario', 'otros']),
            'especificar_tenencia' => null,
            'malla' => fake()->boolean(),
            'hectareas_malla' => null,
            'cierre_perimetral' => fake()->boolean(),
            'lat' => fake()->latitude(-33, -32),
            'lng' => fake()->longitude(-69, -68),
        ];
    }
}
