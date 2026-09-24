<?php

namespace App\Console\Commands;

use App\Models\Usuario;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class CreateAdmin extends Command
{
    protected $signature = 'edessi:admin';

    protected $description = 'Crea una cuenta administradora sin credenciales predeterminadas';

    public function handle(): int
    {
        $data = ['nombre' => $this->ask('Nombre completo'), 'email' => $this->ask('Correo electrónico'), 'password' => $this->secret('Contraseña (mínimo 8 caracteres)')];
        $v = Validator::make($data, ['nombre' => 'required|string|max:255', 'email' => 'required|email|unique:usuarios,email', 'password' => 'required|string|min:8']);
        if ($v->fails()) {
            foreach ($v->errors()->all() as $error) {
                $this->error($error);
            }

return self::FAILURE;
        }
        Usuario::create($data + ['rol' => 'admin']);
        $this->info('Cuenta administradora creada.');

        return self::SUCCESS;
    }
}
