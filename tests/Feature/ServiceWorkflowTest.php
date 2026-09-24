<?php

namespace Tests\Feature;

use App\Mail\EquipoListoMail;
use App\Models\Cliente;
use App\Models\Equipo;
use App\Models\Reparacion;
use App\Models\Usuario;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ServiceWorkflowTest extends TestCase
{
    use RefreshDatabase;

    private function order(?Usuario $tech = null, string $state = 'recibido'): Reparacion
    {
        $u = Usuario::factory()->create(['rol' => 'cliente']);
        $c = Cliente::create(['usuario_id' => $u->id, 'nombre' => 'Cliente de prueba', 'ci' => 'CI-'.$u->id, 'correo_notificacion' => 'cliente'.$u->id.'@example.com']);
        $e = Equipo::create(['cliente_id' => $c->id, 'tipo' => 'Laptop', 'marca' => 'HP', 'modelo' => 'ProBook', 'numero_serie' => 'SERIE-'.$u->id, 'accesorios' => 'Cargador', 'estado_visual' => 'Sin golpes']);

        return Reparacion::create(['equipo_id' => $e->id, 'tecnico_id' => $tech?->id, 'estado' => $state, 'falla_reportada' => 'No enciende', 'fecha_ingreso' => today()]);
    }

    public function test_every_admin_screen_renders(): void
    {
        $r = $this->order();
        $this->actingAs(Usuario::factory()->create());
        foreach (['/dashboard', '/reparaciones', '/reparaciones-crear', '/reparaciones/'.$r->id, '/equipos', '/equipos-crear', '/equipos/'.$r->equipo_id, '/equipos/'.$r->equipo_id.'/editar', '/clientes', '/clientes-crear', '/clientes/'.$r->equipo->cliente_id, '/clientes/'.$r->equipo->cliente_id.'/editar', '/usuarios', '/usuarios/create', '/reportes', '/profile'] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_client_can_only_see_own_equipment_and_orders(): void
    {
        $mine = $this->order();
        $other = $this->order();
        $this->actingAs($mine->equipo->cliente->usuario);
        $this->get('/reparaciones/'.$mine->id)->assertOk();
        $this->get('/reparaciones/'.$other->id)->assertForbidden();
        $this->get('/equipos/'.$other->equipo_id)->assertForbidden();
        $this->get('/equipos/'.$mine->equipo_id)->assertOk();
        $this->get('/dashboard')->assertOk();
        $this->get('/reparaciones?q='.$other->id)->assertDontSee('/reparaciones/'.$other->id.'"', false);
        foreach (['/clientes', '/usuarios', '/reportes', '/reparaciones-crear', '/equipos-crear', '/reparaciones/'.$mine->id.'/comprobante'] as $url) {
            $this->get($url)->assertForbidden();
        }
        $this->patch('/reparaciones/'.$mine->id.'/estado', ['estado' => 'listo'])->assertForbidden();
    }

    public function test_technician_cannot_edit_another_technicians_order(): void
    {
        $tech = Usuario::factory()->create(['rol' => 'tecnico']);
        $other = Usuario::factory()->create(['rol' => 'tecnico']);
        $r = $this->order($other);
        $this->actingAs($tech);
        $this->get('/reparaciones/'.$r->id)->assertForbidden();
        $this->patch('/reparaciones/'.$r->id.'/estado', ['estado' => 'listo'])->assertForbidden();
        $this->post('/reparaciones/'.$r->id.'/observaciones', ['descripcion' => 'Cambio indebido'])->assertForbidden();
        $this->post('/reparaciones/'.$r->id.'/repuestos', ['repuesto_nuevo' => 'RAM', 'cantidad' => 1])->assertForbidden();
        $this->get('/reparaciones/'.$r->id.'/comprobante')->assertForbidden();
    }

    public function test_technician_can_receive_clients_equipment_and_orders(): void
    {
        $tech = Usuario::factory()->create(['rol' => 'tecnico']);
        $this->actingAs($tech);
        $this->get('/dashboard')->assertOk();
        $this->get('/clientes-crear')->assertOk();
        $this->get('/equipos-crear')->assertOk();
        $this->post('/clientes', ['nombre' => 'Nueva cliente', 'ci' => '987654'])->assertSessionHasNoErrors();
        $c = Cliente::where('ci', '987654')->firstOrFail();
        $this->assertNull($c->usuario_id);
        $this->post('/equipos', ['cliente_id' => $c->id, 'tipo' => 'Laptop', 'accesorios' => 'Cargador', 'estado_visual' => 'RayÃ³n en tapa'])->assertSessionHasNoErrors();
        $e = Equipo::where('cliente_id', $c->id)->firstOrFail();
        $this->post('/reparaciones', ['equipo_id' => $e->id, 'falla_reportada' => 'No inicia', 'fecha_ingreso' => today()->toDateString()])->assertSessionHasNoErrors();
        $r = Reparacion::firstOrFail();
        $this->assertSame($tech->id, $r->tecnico_id);
        $this->assertSame(1, $r->historial()->count());
        $this->get('/usuarios')->assertForbidden();
        $this->get('/clientes/'.$c->id.'/editar')->assertForbidden();
    }

    public function test_duplicate_open_order_and_future_reception_are_rejected(): void
    {
        $r = $this->order();
        $this->actingAs(Usuario::factory()->create());
        $data = ['equipo_id' => $r->equipo_id, 'falla_reportada' => 'Otra falla', 'fecha_ingreso' => today()->toDateString()];
        $this->post('/reparaciones', $data)->assertSessionHasErrors('equipo_id');
        $this->post('/reparaciones', array_merge($data, ['fecha_ingreso' => today()->addDay()->toDateString()]))->assertSessionHasErrors('fecha_ingreso');
        $this->assertDatabaseCount('reparaciones', 1);
    }

    public function test_assignment_only_accepts_active_technicians(): void
    {
        $admin = Usuario::factory()->create();
        $inactive = Usuario::factory()->create(['rol' => 'tecnico', 'activo' => false]);
        $tech = Usuario::factory()->create(['rol' => 'tecnico']);
        $r = $this->order();
        $this->actingAs($admin);
        foreach ([$admin->id, $inactive->id] as $id) {
            $this->post('/reparaciones/'.$r->id.'/asignar', ['tecnico_id' => $id])->assertSessionHasErrors('tecnico_id');
        }
        $this->post('/reparaciones/'.$r->id.'/asignar', ['tecnico_id' => $tech->id])->assertSessionHasNoErrors();
        $this->assertSame($tech->id, $r->fresh()->tecnico_id);
    }

    public function test_order_can_only_be_claimed_once_and_not_after_delivery(): void
    {
        $a = Usuario::factory()->create(['rol' => 'tecnico']);
        $b = Usuario::factory()->create(['rol' => 'tecnico']);
        $r = $this->order();
        $this->actingAs($a)->post('/reparaciones/'.$r->id.'/autoasignar')->assertSessionHas('success');
        $this->actingAs($b)->post('/reparaciones/'.$r->id.'/autoasignar')->assertSessionHas('error');
        $this->assertSame($a->id, $r->fresh()->tecnico_id);
        $closed = $this->order(null, 'entregado');
        $this->post('/reparaciones/'.$closed->id.'/autoasignar')->assertSessionHas('error');
        $this->assertNull($closed->fresh()->tecnico_id);
    }

    public function test_status_history_delivery_and_ready_notification(): void
    {
        Mail::fake();
        $t = Usuario::factory()->create(['rol' => 'tecnico']);
        $r = $this->order($t);
        $this->actingAs($t);
        $this->patch('/reparaciones/'.$r->id.'/estado', ['estado' => 'entregado'])->assertSessionHasErrors('estado');
        foreach (['diagnostico', 'reparacion', 'listo'] as $state) {
            $this->patch('/reparaciones/'.$r->id.'/estado', ['estado' => $state])->assertSessionHasNoErrors();
        }
        Mail::assertQueued(EquipoListoMail::class, 1);
        $this->assertDatabaseCount('notifications', 1);
        $this->assertSame(3, $r->historial()->count());
        $this->patch('/reparaciones/'.$r->id.'/estado', ['estado' => 'listo']);
        Mail::assertQueued(EquipoListoMail::class, 1);
        $this->patch('/reparaciones/'.$r->id.'/estado', ['estado' => 'entregado'])->assertSessionHasNoErrors();
        $this->assertEquals(today(), $r->fresh()->fecha_entrega);
        $this->patch('/reparaciones/'.$r->id.'/estado', ['estado' => 'recibido'])->assertSessionHasErrors('estado');
        $this->assertSame('entregado', $r->fresh()->estado);
        $this->post('/reparaciones/'.$r->id.'/observaciones', ['descripcion' => 'EdiciÃ³n tardÃ­a'])->assertStatus(422);
    }

    public function test_diagnosis_cost_and_parts_are_saved_without_duplicate_pivots(): void
    {
        $r = $this->order();
        $this->actingAs(Usuario::factory()->create());
        $this->patch('/reparaciones/'.$r->id, ['falla_reportada' => 'No enciende', 'diagnostico' => 'Fuente daÃ±ada', 'costo' => '125.50', 'fecha_estimada' => today()->toDateString()])->assertSessionHasNoErrors();
        $this->assertSame('125.50', $r->fresh()->costo);
        $this->patch('/reparaciones/'.$r->id, ['falla_reportada' => 'No enciende', 'costo' => -1])->assertSessionHasErrors('costo');
        $this->post('/reparaciones/'.$r->id.'/repuestos', ['repuesto_nuevo' => 'Memoria RAM', 'cantidad' => 2])->assertSessionHasNoErrors();
        $this->post('/reparaciones/'.$r->id.'/repuestos', ['repuesto_nuevo' => 'Memoria RAM', 'cantidad' => 1])->assertSessionHasNoErrors();
        $this->assertDatabaseCount('repuestos_usados', 1);
        $this->assertSame(3, $r->repuestos()->first()->pivot->cantidad);
    }

    public function test_deactivated_accounts_lose_existing_sessions_and_cannot_login(): void
    {
        $u = Usuario::factory()->create();
        $this->actingAs($u);
        $u->update(['activo' => false]);
        $this->get('/dashboard')->assertRedirect('/login');
        $this->assertGuest();
        $this->post('/login', ['email' => $u->email, 'password' => 'password'])->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_client_email_alias_login_and_password_recovery_work(): void
    {
        Notification::fake();
        $r = $this->order();
        $c = $r->equipo->cliente;
        $c->usuario->update(['email' => $c->ci]);
        $this->post('/login', ['email' => $c->correo_notificacion, 'password' => 'password'])->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($c->usuario);
        $this->post('/logout');
        $this->post('/forgot-password', ['email' => $c->ci])->assertSessionHas('status');
        Notification::assertSentTo($c->usuario, ResetPassword::class, function ($n) use ($c) {
            $this->post('/reset-password', ['email' => $c->ci, 'token' => $n->token, 'password' => 'Nuevo-1234', 'password_confirmation' => 'Nuevo-1234'])->assertSessionHasNoErrors()->assertRedirect('/login');

            return true;
        });
        $this->assertTrue(Hash::check('Nuevo-1234', $c->usuario->fresh()->password));
    }

    public function test_public_tracking_requires_matching_order_and_ci_and_hides_contact_data(): void
    {
        $r = $this->order();
        $other = $this->order();
        $this->get('/seguimiento')->assertOk();
        $this->post('/seguimiento', ['orden' => $r->id, 'ci' => $other->equipo->cliente->ci])->assertSessionHasErrors('orden');
        $this->post('/seguimiento', ['orden' => $r->id, 'ci' => $r->equipo->cliente->ci])->assertRedirect(route('seguimiento.show', $r->codigo_seguimiento));
        $this->get('/seguimiento/'.$r->codigo_seguimiento)->assertOk()->assertSee('No enciende')->assertDontSee($r->equipo->cliente->correo_notificacion)->assertDontSee($r->equipo->cliente->ci)->assertHeader('X-Robots-Tag', 'noindex, nofollow');
        $this->get('/seguimiento/'.$r->id)->assertNotFound();
    }

    public function test_reports_filter_validate_dates_and_scope_technicians(): void
    {
        $a = Usuario::factory()->create(['rol' => 'tecnico']);
        $b = Usuario::factory()->create(['rol' => 'tecnico']);
        $mine = $this->order($a);
        $other = $this->order($b);
        $this->actingAs($a);
        $this->get('/reportes?tecnico_id='.$b->id)->assertOk()->assertSee('/reparaciones/'.$mine->id.'"', false)->assertDontSee('/reparaciones/'.$other->id.'"', false);
        $this->get('/reportes/generar?fecha_desde=2026-09-20&fecha_hasta=2026-09-01')->assertSessionHasErrors('fecha_hasta');
    }

    public function test_all_pdf_exports_are_valid_pdf_responses(): void
    {
        $r = $this->order();
        $this->actingAs(Usuario::factory()->create());
        foreach (['/reparaciones/'.$r->id.'/comprobante', '/reportes/generar', '/clientes/'.$r->equipo->cliente_id.'/historial-pdf'] as $url) {
            $res = $this->get($url)->assertOk()->assertHeader('Content-Type', 'application/pdf');
            $this->assertStringStartsWith('%PDF-', $res->getContent());
        }
    }

    public function test_deletion_does_not_destroy_existing_history(): void
    {
        $admin = Usuario::factory()->create();
        $r = $this->order(null, 'reparacion');
        $this->actingAs($admin);
        $this->delete('/clientes/'.$r->equipo->cliente_id)->assertSessionHas('error');
        $this->delete('/equipos/'.$r->equipo_id)->assertSessionHas('error');
        $this->delete('/reparaciones/'.$r->id)->assertSessionHas('error');
        $this->delete('/usuarios/'.$admin->id)->assertSessionHas('error');
        $this->patch('/usuarios/'.$admin->id.'/toggle')->assertSessionHas('error');
        $this->assertDatabaseCount('reparaciones', 1);
        $this->assertTrue($admin->fresh()->activo);
    }

    public function test_client_profile_cannot_change_identity_or_elevate_role(): void
    {
        $r = $this->order();
        $u = $r->equipo->cliente->usuario;
        $this->actingAs($u)->patch('/profile', ['nombre' => 'Nombre actualizado', 'email' => 'otro', 'rol' => 'admin'])->assertSessionHasNoErrors();
        $this->assertSame('cliente',$u->fresh()->rol);
        $this->assertSame($u->email,$u->fresh()->email);
        $this->assertSame('Nombre actualizado',$r->equipo->cliente->fresh()->nombre);
    }

    public function test_only_admin_can_create_and_disable_customer_access(): void {
        $data=['nombre'=>'Cliente con acceso','ci'=>'2222333','crear_acceso'=>1,'password'=>'Segura-123','password_confirmation'=>'Segura-123'];
        $tech=Usuario::factory()->create(['rol'=>'tecnico']);
        $this->actingAs($tech)->post('/clientes',$data)->assertSessionHasErrors('crear_acceso');
        $this->assertDatabaseCount('clientes',0);
        $this->actingAs(Usuario::factory()->create())->post('/clientes',$data)->assertSessionHasNoErrors();
        $c=Cliente::where('ci','2222333')->firstOrFail();
        $this->assertTrue(Hash::check('Segura-123',$c->usuario->password));
        $this->get('/clientes/'.$c->id.'/editar')->assertOk()->assertSee('Estado de la cuenta');
        $this->patch('/clientes/'.$c->id,['nombre'=>$c->nombre,'ci'=>$c->ci,'acceso_activo'=>0])->assertSessionHasNoErrors();
        $this->assertFalse($c->usuario->fresh()->activo);
    }
}
