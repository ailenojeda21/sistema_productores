<?php

namespace Database\Factories;

use App\Models\StaffUser;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<StaffUser>
 */
class StaffUserFactory extends Factory
{
    protected $model = StaffUser::class;

    /**
     * El hash se calcula una sola vez por proceso de test.
     */
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'role' => 'admin',
            'active' => true,
            'last_login_at' => fake()->optional()->dateTimeBetween('-90 days', 'now'),
            'last_access_at' => fake()->optional()->dateTimeBetween('-30 days', 'now'),
            'remember_token' => Str::random(10),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => ['role' => 'admin']);
    }

    /**
     * Rol auditor: según la Decisión A3 de AGENTS.md tiene los mismos
     * permisos de consulta y exportación que el admin; lo único que no puede
     * es gestionar usuarios staff.
     */
    public function auditor(): static
    {
        return $this->state(fn (array $attributes) => ['role' => 'auditor']);
    }

    /**
     * `EnsureStaffActive` bloquea el acceso de las cuentas inactivas.
     */
    public function inactivo(): static
    {
        return $this->state(fn (array $attributes) => [
            'active' => false,
            'last_login_at' => null,
        ]);
    }

    /**
     * Nunca se logueó: útil para la columna de la tabla de usuarios staff.
     */
    public function nuncaIngreso(): static
    {
        return $this->state(fn (array $attributes) => [
            'last_login_at' => null,
        ]);
    }

    public function eliminado(): static
    {
        return $this->state(fn (array $attributes) => [
            'deleted_at' => now(),
        ]);
    }
}
