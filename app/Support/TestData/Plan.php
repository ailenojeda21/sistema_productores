<?php

namespace App\Support\TestData;

/**
 * Dataset de prueba completo, resuelto en memoria antes de tocar la base.
 *
 * Separar "planificar" de "persistir" permite:
 *  - verificar los invariantes (C1..C10 de docs/datos_prueba.md) sin BD;
 *  - que cada seeder de cohorte solo ejecute su porción del plan;
 *  - que el dataset sea idéntico entre ejecuciones.
 */
final class Plan
{
    /**
     * @param  array<int, array<string, mixed>>  $usuarios
     * @param  array<int, array<string, mixed>>  $staff
     */
    public function __construct(
        public readonly string $perfil,
        public readonly int $semilla,
        public readonly string $password,
        public readonly array $usuarios,
        public readonly array $staff,
    ) {}

    /**
     * @return array<int, array<string, mixed>>
     */
    public function propiedadesDe(int $indiceUsuario): array
    {
        return $this->usuarios[$indiceUsuario]['propiedades'] ?? [];
    }

    public function totalPropiedades(): int
    {
        $total = 0;

        foreach ($this->usuarios as $usuario) {
            $total += count($usuario['propiedades']);
        }

        return $total;
    }

    public function totalCultivos(): int
    {
        $total = 0;

        foreach ($this->usuarios as $usuario) {
            foreach ($usuario['propiedades'] as $propiedad) {
                $total += count($propiedad['cultivos']);
            }
        }

        return $total;
    }

    public function totalMaquinarias(): int
    {
        $total = 0;

        foreach ($this->usuarios as $usuario) {
            $total += count($usuario['maquinarias']);
        }

        return $total;
    }

    public function totalComercios(): int
    {
        $total = 0;

        foreach ($this->usuarios as $usuario) {
            if ($usuario['comercio'] !== null) {
                $total++;
            }
        }

        return $total;
    }
}
