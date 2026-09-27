<?php

namespace Database\Factories;

use App\Models\Propiedad;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Propiedad>
 */
class PropiedadFactory extends Factory
{
    protected $model = Propiedad::class;

    /**
     * `required_if` de `StorePropiedadRequest`: `especificar_tenencia` es
     * obligatorio solo cuando `tipo_tenencia` es `otros`, y debe quedar en NULL
     * en cualquier otro caso. Se resuelve en `afterMaking` y no en
     * `definition()` para que un `create(['tipo_tenencia' => ...])` no herede
     * el valor calculado con el `tipo_tenencia` sorteado al azar.
     */
    public function configure(): static
    {
        return $this->afterMaking(function (Propiedad $propiedad) {
            $propiedad->especificar_tenencia = $propiedad->tipo_tenencia === 'otros'
                ? ($propiedad->especificar_tenencia ?: fake()->randomElement([
                    'Usufructo familiar', 'Sucesión testamentaria', 'Concesión municipal',
                ]))
                : null;
        });
    }

    public function definition(): array
    {
        $malla = fake()->boolean();
        $derechoRiego = fake()->boolean();
        $rut = fake()->boolean();
        $tipoTenencia = fake()->randomElement(array_keys(Propiedad::TIPO_TENENCIA));

        return [
            'usuario_id' => User::factory(),
            'calle' => fake()->streetName(),
            'numeracion' => (string) fake()->numberBetween(1, 9999),
            'distrito' => fake()->randomElement(array_keys(Propiedad::DISTRITOS)),
            'hectareas' => fake()->randomFloat(2, 5, 120),
            // `derecho_riego = 0` exige `tipo_derecho_riego = NULL`, igual que
            // en `StorePropiedadRequest`.
            'derecho_riego' => $derechoRiego,
            'tipo_derecho_riego' => $derechoRiego
                ? fake()->randomElement(array_keys(Propiedad::TIPO_DERECHO_RIEGO))
                : null,
            // `rut = 1` exige `rut_valor`; `rut = 0` lo deja en NULL.
            'rut' => $rut,
            'rut_valor' => $rut ? fake()->numerify('########') : null,
            'tipo_tenencia' => $tipoTenencia,
            // `especificar_tenencia` se resuelve en `configure()`, despues de
            // aplicados los overrides: calcularlo aqui lo desincroniza del
            // `tipo_tenencia` que el caller pase por `create([...])`.
            'malla' => $malla,
            // `lte:hectareas` en el request; se mantiene dentro del rango.
            'hectareas_malla' => $malla ? fake()->randomFloat(2, 0.5, 4) : null,
            'cierre_perimetral' => fake()->boolean(),
            'lat' => fake()->latitude(-33.1, -32.55),
            'lng' => fake()->longitude(-69, -68.6),
        ];
    }

    /**
     * Extremos de `lat between:-90,90` y `lng between:-180,180`.
     */
    public function conCoordenadasExtremas(): static
    {
        return $this->state(fn (array $attributes) => [
            'lat' => fake()->randomElement([-90, 90]),
            'lng' => fake()->randomElement([-180, 180]),
        ]);
    }

    /**
     * Hectáreas en 0: límite inferior de `hectareas`.
     */
    public function sinHectareas(): static
    {
        return $this->state(fn (array $attributes) => [
            'hectareas' => 0,
            'hectareas_malla' => null,
        ]);
    }

    /**
     * Hectáreas en 1000: límite superior de `hectareas`.
     */
    public function conHectareasMaximas(): static
    {
        return $this->state(fn (array $attributes) => [
            'hectareas' => 1000,
            'hectareas_malla' => null,
        ]);
    }

    /**
     * RUT de 15 dígitos: el máximo de `rut_valor`.
     */
    public function conRutLargo(): static
    {
        return $this->state(fn (array $attributes) => [
            'rut' => true,
            'rut_valor' => fake()->numerify('###############'),
        ]);
    }

    /**
     * RUT con ceros iniciales: el export PDF hace `floor((float) $rut_valor)`
     * y los pierde, así que sirve para ver esa diferencia.
     */
    public function conRutEnCeros(): static
    {
        return $this->state(fn (array $attributes) => [
            'rut' => true,
            'rut_valor' => '0'.fake()->numerify('########'),
        ]);
    }

    /**
     * Dato inconsistente a propósito: `rut = 0` con `rut_valor` presente. El
     * filtro `rut` de `StaffProducerController` exige `rut = 1`, así que esta
     * fila no debe aparecer nunca.
     */
    public function conRutHuerfano(): static
    {
        return $this->state(fn (array $attributes) => [
            'rut' => false,
            'rut_valor' => fake()->numerify('########'),
        ]);
    }

    /**
     * `malla = 1` sin valor: el export XLSX emite `'0.00'` en vez de celda
     * vacía (`$prop->hectareas_malla ?? '0.00'`).
     */
    public function conMallaSinValor(): static
    {
        return $this->state(fn (array $attributes) => [
            'malla' => true,
            'hectareas_malla' => null,
        ]);
    }

    /**
     * Distrito fuera del whitelist. `StorePropiedadRequest` no tiene regla
     * `in:` para `distrito`, así que la columna admite texto libre de hasta
     * 100 caracteres y `distrito_label` cae al fallback.
     */
    public function conDistritoLibre(string $distrito = 'Zona-Norte-Lavalle'): static
    {
        return $this->state(fn (array $attributes) => [
            'distrito' => $distrito,
        ]);
    }

    /**
     * Distrito de varias palabras, como los de `Propiedad::DISTRITOS`.
     */
    public function enDistrito(string $distrito): static
    {
        return $this->state(fn (array $attributes) => [
            'distrito' => $distrito,
        ]);
    }
}
