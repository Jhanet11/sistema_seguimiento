<?php

namespace App\Http\Requests\Auth;

use App\Models\Usuario;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Antes era 'email', pero admite C.I. (clientes) o correo (admin/técnico)
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $identity = trim($this->string('email')->toString());
        $account = Usuario::where('email', $identity)->first();
        if (! $account) {
            $matches = Usuario::where('rol', 'cliente')->whereHas('cliente', fn ($c) => $c->where('correo_notificacion', $identity))->get();
            $account = $matches->count() === 1 ? $matches->first() : null;
        }
        if (! $account || ! Auth::attempt(['email' => $account->email, 'password' => $this->input('password'), 'activo' => true], $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        // Si el usuario fue desactivado por el admin, no puede iniciar sesión
        if (! Auth::user()->activo) {
            Auth::logout();

            throw ValidationException::withMessages([
                'email' => 'Tu cuenta está desactivada. Contacta al administrador.',
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
