<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Fuerza que todas las URLs generadas (route(), url(), etc.)
        // usen siempre APP_URL del .env, sin importar desde qué
        // dirección (localhost, IP, etc.) se accedió al sistema.
        if (config('app.url')) {
            URL::forceRootUrl(config('app.url'));
        }
    }
}