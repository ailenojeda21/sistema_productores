<?php

namespace Database\Factories;

use App\Models\Maquinaria;
use App\Models\Propiedad;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Maquinaria>
 */
class MaquinariaFactory extends Factory
{
    protected $model = Maquinaria::class;

    public function definition(): array
    {
        $tractor = fake()->boolean();

        return [
            'propiedad_id' => Propiedad::factory(),
            // `tractor = 1` exige `modelo_tractor` (required_if) y
            // `tractor = 0` lo deja en NULL.
            'tractor' => $tractor,
            'modelo_tractor' => $tractor ? fake()->numberBetween(1990, (int) date('Y')) : null,
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

    /**
     * Todos los implementos en false, con o sin tractor.
     */
    public function minima(): static
    {
        $flags = array_fill_keys(array_keys(Maquinaria::IMPLEMENTOS_LABELS), false);

        return $this->state(fn (array $attributes) => $flags + [
            'tractor' => false,
            'modelo_tractor' => null,
        ]);
    }

    /**
     * Sin tractor: `modelo_tractor` queda en NULL.
     */
    public function sinTractor(): static
    {
        return $this->state(fn (array $attributes) => [
            'tractor' => false,
            'modelo_tractor' => null,
        ]);
    }

    /**
     * Límites de `modelo_tractor`: `min:1900` y `max:{date('Y')}`.
     */
    public function conTractorEnElLimiteInferior(): static
    {
        return $this->state(fn (array $attributes) => [
            'tractor' => true,
            'modelo_tractor' => 1900,
        ]);
    }

    public function conTractorEnElLimiteSuperior(): static
    {
        return $this->state(fn (array $attributes) => [
            'tractor' => true,
            'modelo_tractor' => (int) date('Y'),
        ]);
    }

    /**
     * Implementos coherentes con una cultura vitícola.
     */
    public function paraViticola(): static
    {
        return $this->state(fn (array $attributes) => [
            'cosechadora' => true,
            'enfardadora' => true,
        ]);
    }

    /**
     * Implementos coherentes con una cultura hortícola.
     */
    public function paraHorticola(): static
    {
        return $this->state(fn (array $attributes) => [
            'desmalezadora' => true,
            'pulverizadora_tractor' => true,
        ]);
    }
}
