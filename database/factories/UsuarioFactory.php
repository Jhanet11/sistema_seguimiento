<?php

namespace Database\Factories;

use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsuarioFactory extends Factory
{
    protected $model = Usuario::class;

    public function definition(): array
    {
        return ['nombre' => fake()->name(), 'email' => fake()->unique()->safeEmail(), 'password' => Hash::make('password'), 'rol' => 'admin', 'activo' => true, 'remember_token' => Str::random(10)];
    }
}
