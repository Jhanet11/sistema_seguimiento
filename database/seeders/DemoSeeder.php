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

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment('local') || Usuario::exists()) {
            throw new \RuntimeException('La demo requiere entorno local y una base sin usuarios.');
        }
        // --- Usuarios ---
        $admin = Usuario::create([
            'nombre' => 'Admin EDESSI',
            'email' => 'admin@edessi.com',
            'password' => Hash::make('DemoEdessi2026!'),
            'rol' => 'admin',
        ]);

        $tecnico1 = Usuario::create([
            'nombre' => 'Carlos Mamani',
            'email' => 'carlos@edessi.com',
            'password' => Hash::make('DemoEdessi2026!'),
            'rol' => 'tecnico',
        ]);

        $tecnico2 = Usuario::create([
            'nombre' => 'Ana Quispe',
            'email' => 'ana@edessi.com',
            'password' => Hash::make('DemoEdessi2026!'),
            'rol' => 'tecnico',
        ]);

        $usuarioCliente1 = Usuario::create([
            'nombre' => 'Jhanet Rojas',
            'email' => '8451236', // login = C.I.
            'password' => Hash::make('DemoEdessi2026!'),
            'rol' => 'cliente',
        ]);

        $usuarioCliente2 = Usuario::create([
            'nombre' => 'Luis Fernandez',
            'email' => '9223451', // login = C.I.
            'password' => Hash::make('DemoEdessi2026!'),
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
            'fecha_estimada' => now()->addDays(2), 'diagnostico' => 'Se identificó una falla en la alimentación. Se reemplazará el componente dañado.', 'costo' => 180,
        ]);

        $reparacion2 = Reparacion::create([
            'equipo_id' => $equipo2->id,
            'tecnico_id' => null, // sin asignar (para probar autoasignación)
            'falla_reportada' => 'Muy lento, posible cambio a SSD',
            'estado' => 'recibido',
            'fecha_ingreso' => now()->subDays(1),
        ]);

        foreach ([['Lenovo', 'ThinkPad E14', 'diagnostico', 'Pantalla con líneas verticales'], ['Epson', 'EcoTank L3250', 'listo', 'Mantenimiento de cabezal de impresión'], ['Asus', 'VivoBook 15', 'recibido', 'Se apaga durante el uso'], ['Dell', 'Latitude 5420', 'entregado', 'Actualización de memoria RAM'], ['HP', 'ProDesk 400', 'reparacion', 'El sistema no inicia'], ['Acer', 'Aspire 5', 'diagnostico', 'Teclado no responde']] as $i => [$marca,$modelo,$estado,$falla]) {
            $e = Equipo::create(['cliente_id' => $i % 2 ? $cliente2->id : $cliente1->id, 'tipo' => $marca === 'Epson' ? 'Impresora' : 'Laptop', 'marca' => $marca, 'modelo' => $modelo, 'numero_serie' => 'DEMO-'.($i + 3), 'accesorios' => 'Cable de alimentación', 'estado_visual' => 'Equipo usado, sin daños visibles']);
            $r = Reparacion::create(['equipo_id' => $e->id, 'tecnico_id' => $estado === 'recibido' ? null : ($i % 2 ? $tecnico2->id : $tecnico1->id), 'falla_reportada' => $falla, 'estado' => $estado, 'fecha_ingreso' => now()->subDays($i + 1), 'fecha_entrega' => $estado === 'entregado' ? today() : null, 'fecha_estimada' => now()->addDays(2), 'costo' => $estado === 'listo' || $estado === 'entregado' ? 150 : null]);
            $r->historial()->create(['usuario_id' => $admin->id, 'estado' => $estado]);
        }
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
