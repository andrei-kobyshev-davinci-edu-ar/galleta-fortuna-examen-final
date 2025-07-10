<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFortunasTable extends Migration
{
    public function up()
    {
        Schema::create('fortunas', function (Blueprint $table) {
            $table->id();
            $table->text('mensaje');
            $table->unsignedBigInteger('agregado_por')->nullable();
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->foreign('agregado_por')->references('id')->on('usuarios')->onDelete('set null');
            $table->index('fecha_creacion', 'idx_fecha');
        });
    }

    public function down()
    {
        Schema::dropIfExists('fortunas');
    }
}