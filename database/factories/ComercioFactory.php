<?php

namespace Database\Factories;

use App\Models\Comercio;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Comercio>
 */
class ComercioFactory extends Factory
{
    protected $model = Comercio::class;

    public function definition(): array
    {
        $mercados = fake()->randomElements(array_keys(Comercio::MERCADOS), fake()->numberBetween(1, 3));
        $cooperativas = fake()->randomElements(array_keys(Comercio::COOPERATIVAS), fake()->numberBetween(1, 2));

        return [
            'usuario_id' => User::factory(),
            'infraestructura_empaque' => fake()->boolean(),
            // `ComercioController` rechaza la combinación sin ninguna opción de
            // venta, así que el default garantiza al menos una.
            'vende_en_finca' => false,
            // Se guardan las CLAVES: el export las resuelve con
            // `Comercio::MERCADOS[$k] ?? $k`.
            'mercados' => $mercados,
            'cooperativas' => $cooperativas,
        ];
    }

    /**
     * Vende únicamente en la finca: sin mercados ni cooperativas.
     */
    public function soloEnFinca(): static
    {
        return $this->state(fn (array $attributes) => [
            'vende_en_finca' => true,
            'mercados' => [],
            'cooperativas' => [],
        ]);
    }

    /**
     * `mercados = NULL`, que es lo que emite el formulario cuando el usuario
     * no tildó ninguno y no marca la finca.
     */
    public function sinMercados(): static
    {
        return $this->state(fn (array $attributes) => [
            'mercados' => null,
            'cooperativas' => fake()->randomElements(
                array_keys(Comercio::COOPERATIVAS),
                fake()->numberBetween(1, 2)
            ),
        ]);
    }

    /**
     * JSON con una etiqueta que `Rule::in()` rechazaría pero que la columna
     * admite. El export la emite tal cual por el `?? $k`.
     */
    public function conMercadoHuerfano(string $mercado = 'Mercado Inexistente'): static
    {
        return $this->state(fn (array $attributes) => [
            'mercados' => [$mercado],
        ]);
    }
}
