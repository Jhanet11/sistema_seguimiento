<?php

namespace Tests\Feature\Auth;

use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_unconfigured_email_verification_is_not_exposed(): void
    {
        $this->actingAs(Usuario::factory()->create())->get('/verify-email')->assertNotFound();
    }
}
