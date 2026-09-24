<?php

namespace Tests\Feature;

use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_uses_nombre_and_usuarios_table(): void
    {
        $u = Usuario::factory()->create();
        $this->actingAs($u)->get('/profile')->assertOk()->assertSee($u->nombre);
        $this->from('/profile')->patch('/profile', ['nombre' => 'Nombre actualizado', 'email' => 'nuevo@example.com'])->assertSessionHasNoErrors()->assertRedirect('/profile');
        $this->assertSame('Nombre actualizado', $u->fresh()->nombre);
        $this->assertSame('nuevo@example.com', $u->fresh()->email);
    }

    public function test_profile_rejects_existing_staff_email(): void
    {
        $a = Usuario::factory()->create();
        $b = Usuario::factory()->create();
        $this->actingAs($a)->patch('/profile', ['nombre' => 'Nombre', 'email' => $b->email])->assertSessionHasErrors('email');
    }

    public function test_accounts_cannot_self_delete_and_erase_their_history(): void
    {
        $u = Usuario::factory()->create();
        $this->actingAs($u)->delete('/profile', ['password' => 'password'])->assertStatus(405);
        $this->assertNotNull($u->fresh());
    }
}
