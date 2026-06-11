<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('numero_pedido')->unique();
            $table->string('cliente_nombre');
            $table->string('cliente_telefono');
            $table->string('cliente_correo')->nullable();
            $table->text('cliente_direccion');
            $table->string('estado')->default('no_revisado');
            $table->decimal('total', 12, 2)->default(0);
            $table->decimal('total_iva', 12, 2)->default(0);
            $table->boolean('es_vip')->default(false);
            $table->text('notas')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('estado');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
