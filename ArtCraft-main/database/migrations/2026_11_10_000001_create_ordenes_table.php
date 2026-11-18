<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ordenes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nombre_cliente');
            $table->string('email');
            $table->string('telefono');
            $table->string('direccion');
            $table->string('ciudad');
            $table->string('referencia')->unique(); // referencia de Wompi
            $table->unsignedInteger('subtotal'); // en centavos (COP)
            $table->unsignedInteger('envio');
            $table->unsignedInteger('total'); // en centavos (COP)
            $table->string('moneda')->default('COP');
            $table->string('estado')->default('pendiente'); // pendiente, aprobado, declinado, error
            $table->string('wompi_transaction_id')->nullable();
            $table->string('wompi_status')->nullable();
            $table->text('metodo_pago'); // tarjeta, transferencia, contraentrega
            $table->text('respuesta_wompi')->nullable(); // JSON de la respuesta de Wompi
            $table->timestamps();

            $table->index('referencia');
            $table->index('estado');
            $table->index('usuario_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ordenes');
    }
};
