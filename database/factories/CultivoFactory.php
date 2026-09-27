<?php

namespace Database\Factories;

use App\Models\Cultivo;
use App\Models\Propiedad;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Cultivo>
 */
class CultivoFactory extends Factory
{
    protected $model = Cultivo::class;

    public function definition(): array
    {
        $tipo = fake()->randomElement(array_keys(Cultivo::TIPOS));

        return [
            'propiedad_id' => Propiedad::factory(),
            'tipo' => $tipo,
            // El par (tipo, variedad) siempre es coherente.
            'variedad' => fake()->randomElement(array_keys(Cultivo::getVariedadesForTipo($tipo))),
            'estacion' => fake()->randomElement(array_keys(Cultivo::ESTACIONES)),
            'hectareas' => fake()->randomFloat(2, 0.5, 12),
            'manejo_cultivo' => fake()->randomElement(array_keys(Cultivo::MANEJO_OPTIONS)),
            'tecnologia_riego' => fake()->randomElement(array_keys(Cultivo::TECNOLOGIA_RIEGO)),
        ];
    }

    /**
     * Fija el tipo y elige una variedad de su whitelist.
     */
    public function deTipo(string $tipo): static
    {
        return $this->state(fn (array $attributes) => [
            'tipo' => $tipo,
            'variedad' => fake()->randomElement(array_keys(Cultivo::getVariedadesForTipo($tipo))),
        ]);
    }

    /**
     * Hectáreas en 0: límite inferior admitido por `hectareas`.
     */
    public function sinHectareas(): static
    {
        return $this->state(fn (array $attributes) => [
            'hectareas' => 0,
        ]);
    }

    /**
     * `tecnologia_riego = NULL`. El export XLSX cae al `?? ''` y escribe una
     * celda vacía.
     */
    public function sinRiego(): static
    {
        return $this->state(fn (array $attributes) => [
            'tecnologia_riego' => null,
        ]);
    }

    /**
     * Manejo orgánico, para el filtro por texto libre en la ficha.
     */
    public function organico(): static
    {
        return $this->state(fn (array $attributes) => [
            'manejo_cultivo' => 'Organico',
        ]);
    }
}
