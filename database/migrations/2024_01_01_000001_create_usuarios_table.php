<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsuariosTable extends Migration
{
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('email', 100)->unique();
            $table->string('password', 255);
            $table->enum('rol', ['usuario', 'admin'])->default('usuario');
            $table->timestamp('fecha_registro')->useCurrent();
            $table->index('email', 'idx_email');
        });
    }

    public function down()
    {
        Schema::dropIfExists('usuarios');
    }
}