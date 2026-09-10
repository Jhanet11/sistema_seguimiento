<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reparaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipo_id')->constrained('equipos')->cascadeOnDelete();
            // tecnico_id apunta a usuarios (rol=tecnico); nullable porque puede no estar asignado aun
            $table->foreignId('tecnico_id')->nullable()->constrained('usuarios')->nullOnDelete();
            $table->text('falla_reportada');
            $table->enum('estado', ['recibido', 'diagnostico', 'reparacion', 'listo', 'entregado'])
                  ->default('recibido');
            $table->date('fecha_ingreso');
            $table->date('fecha_entrega')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reparaciones');
    }
};