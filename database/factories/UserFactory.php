<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * El hash se calcula una sola vez: bcrypt es el cuello de botella cuando
     * un test crea decenas de usuarios.
     */
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'dni' => fake()->unique()->numerify('########'),
            'telefono' => fake()->numerify('2614#######'),
            'direccion' => fake()->streetAddress(),
            'avatar' => fake()->randomElement(['uno.png', 'dos.png', 'tres.png', 'cuatro.png', 'cinco.png']),
            'cooperativas' => fake()->optional(0.7)->randomElements(
                array_keys(User::COOPERATIVAS),
                fake()->numberBetween(1, 3)
            ),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Email sin verificar. Combinado con `sinPerfil()` cubre el certificado
     * bloqueado por `MustVerifyEmail`.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Perfil sin datos opcionales: `dni`, `telefono` y `direccion` vacios.
     * `name` y `email` son obligatorios, asi que `profile_completeness` queda
     * en 40 (2 de 5), que es el minimo alcanzable.
     */
    public function sinPerfil(): static
    {
        return $this->state(fn (array $attributes) => [
            'dni' => '',
            'telefono' => '',
            'direccion' => '',
            'avatar' => null,
            'cooperativas' => null,
        ]);
    }

    /**
     * Perfil al 100% (`name`, `email`, `dni`, `telefono`, `direccion`).
     */
    public function perfilCompleto(): static
    {
        return $this->state(fn (array $attributes) => [
            'dni' => fake()->unique()->numerify('########'),
            'telefono' => fake()->numerify('2614#######'),
            'direccion' => fake()->streetAddress(),
        ]);
    }

    /**
     * Productor dado de baja. El hook `User::booted()` anonimiza la fila, así
     * que hay que buscar por el email original si se necesita el id.
     */
    public function eliminado(): static
    {
        return $this->state(fn (array $attributes) => [
            'deleted_at' => now(),
        ]);
    }

    /**
     * Producto sin formato libre en el nombre, para probar que un `%` o un `_`
     * en los DATOS no se interpretan como comodines de LIKE.
     */
    public function conComodinesEnElNombre(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => "Comodin % y _ Cuña O'Neil",
        ]);
    }
}
