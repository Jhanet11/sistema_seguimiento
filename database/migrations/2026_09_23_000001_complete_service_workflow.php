<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('equipos', function (Blueprint $t) {
            $t->text('accesorios')->nullable();
            $t->text('estado_visual')->nullable();
        });
        Schema::table('reparaciones', function (Blueprint $t) {
            $t->text('diagnostico')->nullable();
            $t->decimal('costo', 10, 2)->nullable();
            $t->date('fecha_estimada')->nullable();
            $t->uuid('codigo_seguimiento')->nullable()->unique();
            $t->index(['estado', 'tecnico_id']);
        });
        DB::table('reparaciones')->orderBy('id')->chunkById(100, function ($rows) {
            foreach ($rows as $r) {
                DB::table('reparaciones')->where('id', $r->id)->update(['codigo_seguimiento' => (string) Str::uuid()]);
            }
        });
        Schema::create('historial_estados', function (Blueprint $t) {
            $t->id();
            $t->foreignId('reparacion_id')->constrained('reparaciones')->cascadeOnDelete();
            $t->foreignId('usuario_id')->nullable()->constrained('usuarios')->nullOnDelete();
            $t->string('estado_anterior')->nullable();
            $t->string('estado');
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historial_estados');
        Schema::table('reparaciones', function (Blueprint $t) {
            $t->dropIndex(['estado', 'tecnico_id']);
            $t->dropColumn(['diagnostico', 'costo', 'fecha_estimada', 'codigo_seguimiento']);
        });
        Schema::table('equipos', fn (Blueprint $t) => $t->dropColumn(['accesorios', 'estado_visual']));
    }
};
