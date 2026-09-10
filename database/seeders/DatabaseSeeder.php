<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Equipo;
use App\Models\Observacion;
use App\Models\Reparacion;
use App\Models\Repuesto;
use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // --- Usuarios ---
        $admin = Usuario::create([
            'nombre' => 'Admin EDESSI',
            'email' => 'admin@edessi.com',
            'password' => Hash::make('password'),
            'rol' => 'admin',
        ]);

        $tecnico1 = Usuario::create([
            'nombre' => 'Carlos Mamani',
            'email' => 'carlos@edessi.com',
            'password' => Hash::make('password'),
            'rol' => 'tecnico',
        ]);

        $tecnico2 = Usuario::create([
            'nombre' => 'Ana Quispe',
            'email' => 'ana@edessi.com',
            'password' => Hash::make('password'),
            'rol' => 'tecnico',
        ]);

        $usuarioCliente1 = Usuario::create([
            'nombre' => 'Jhanet Rojas',
            'email' => '8451236', // login = C.I.
            'password' => Hash::make('password'),
            'rol' => 'cliente',
        ]);

        $usuarioCliente2 = Usuario::create([
            'nombre' => 'Luis Fernandez',
            'email' => '9223451', // login = C.I.
            'password' => Hash::make('password'),
            'rol' => 'cliente',
        ]);

        // --- Clientes ---
        $cliente1 = Cliente::create([
            'usuario_id' => $usuarioCliente1->id,
            'nombre' => 'Jhanet Rojas',
            'ci' => '8451236',
            'telefono' => '70011122',
            'direccion' => 'Av. Blanco Galindo km 5',
            'correo_notificacion' => 'jhanet@example.com',
        ]);

        $cliente2 = Cliente::create([
            'usuario_id' => $usuarioCliente2->id,
            'nombre' => 'Luis Fernandez',
            'ci' => '9223451',
            'telefono' => '70033344',
            'direccion' => 'Zona Cala Cala',
        ]);

        // --- Equipos ---
        $equipo1 = Equipo::create([
            'cliente_id' => $cliente1->id,
            'tipo' => 'Laptop',
            'marca' => 'HP',
            'modelo' => 'Pavilion 14',
            'numero_serie' => 'HP-001-2024',
        ]);

        $equipo2 = Equipo::create([
            'cliente_id' => $cliente2->id,
            'tipo' => 'PC de escritorio',
            'marca' => 'Dell',
            'modelo' => 'OptiPlex 3080',
            'numero_serie' => 'DL-002-2024',
        ]);

        // --- Repuestos ---
        $repuesto1 = Repuesto::create(['nombre' => 'Memoria RAM 8GB DDR4']);
        $repuesto2 = Repuesto::create(['nombre' => 'Disco SSD 240GB']);
        $repuesto3 = Repuesto::create(['nombre' => 'Pasta térmica']);

        // --- Reparaciones ---
        $reparacion1 = Reparacion::create([
            'equipo_id' => $equipo1->id,
            'tecnico_id' => $tecnico1->id, // asignada
            'falla_reportada' => 'No enciende, posible falla de fuente',
            'estado' => 'reparacion',
            'fecha_ingreso' => now()->subDays(3),
        ]);

        $reparacion2 = Reparacion::create([
            'equipo_id' => $equipo2->id,
            'tecnico_id' => null, // sin asignar (para probar autoasignación)
            'falla_reportada' => 'Muy lento, posible cambio a SSD',
            'estado' => 'recibido',
            'fecha_ingreso' => now()->subDays(1),
        ]);

        // --- Observaciones ---
        Observacion::create([
            'reparacion_id' => $reparacion1->id,
            'tecnico_id' => $tecnico1->id,
            'descripcion' => 'Se revisó fuente de poder, se detectó capacitor dañado.',
        ]);

        // --- Repuestos usados ---
        $reparacion1->repuestos()->attach($repuesto3->id, ['cantidad' => 1]);
    }
}