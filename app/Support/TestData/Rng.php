<?php

namespace App\Support\TestData;

/**
 * Generador pseudoaleatorio determinista (xorshift32).
 *
 * No usamos `fake()` a propósito: el dataset de pruebas debe ser
 * reproducible byte a byte entre ejecuciones y entre máquinas, y
 * `fake()->unique()` revienta al re-ejecutar un seeder.
 */
final class Rng
{
    private int $estado;

    public function __construct(int $semilla = 20260905)
    {
        $this->estado = ($semilla & 0x7FFFFFFF) ?: 1;
    }

    public function siguiente(): int
    {
        $x = $this->estado;
        $x ^= ($x << 13) & 0x7FFFFFFF;
        $x ^= $x >> 17;
        $x ^= ($x << 5) & 0x7FFFFFFF;

        return $this->estado = $x & 0x7FFFFFFF;
    }

    public function intEntre(int $min, int $max): int
    {
        if ($min > $max) {
            [$min, $max] = [$max, $min];
        }

        return $min + ($this->siguiente() % max(1, $max - $min + 1));
    }

    public function decimalEntre(float $min, float $max, int $decimales = 2): float
    {
        $factor = 10 ** $decimales;

        return round($min + ($this->siguiente() / 0x7FFFFFFF) * ($max - $min), $decimales);
    }

    public function chance(float $probabilidad): bool
    {
        return ($this->siguiente() / 0x7FFFFFFF) < $probabilidad;
    }

    /**
     * @template T
     *
     * @param  array<int|string, T>  $valores
     * @return T
     */
    public function elegir(array $valores): mixed
    {
        $claves = array_keys($valores);

        return $valores[$claves[$this->siguiente() % max(1, count($claves))]];
    }

    /**
     * @param  array<int|string, mixed>  $valores
     * @return array<int, mixed>
     */
    public function elegirN(array $valores, int $cantidad): array
    {
        $claves = array_keys($valores);
        $total = count($claves);

        if ($total === 0 || $cantidad <= 0) {
            return [];
        }

        $tomados = [];

        for ($i = 0; $i < min($cantidad, $total); $i++) {
            $indice = ($this->siguiente() + $i * 7919) % $total;

            while (in_array($indice, $tomados, true)) {
                $indice = ($indice + 1) % $total;
            }

            $tomados[] = $indice;
        }

        return array_map(fn (int $i) => $valores[$claves[$i]], $tomados);
    }

    /**
     * @param  array<int|string, mixed>  $valores
     * @return array<int, mixed>
     */
    public function revolver(array $valores): array
    {
        $claves = array_keys($valores);
        $total = count($claves);

        for ($i = $total - 1; $i > 0; $i--) {
            $j = $this->siguiente() % ($i + 1);
            [$claves[$i], $claves[$j]] = [$claves[$j], $claves[$i]];
        }

        return array_map(fn ($clave) => $valores[$clave], $claves);
    }

    /**
     * @param  array<int, mixed>  $valores
     * @return array<int, mixed>
     */
    public function muestra(array $valores, int $cantidad): array
    {
        return $this->elegirN($valores, $cantidad);
    }
}
