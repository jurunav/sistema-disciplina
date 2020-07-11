<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSancionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sanciones', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre', 250)->unique();
            $table->integer('puntaje')->nullable();
            $table->decimal('puntaje_dia', 8, 2)->nullable();
            $table->string('categoria', 100);
            $table->integer('articulo')->nullable();
            $table->string('grupo', 10)->nullable();
            $table->integer('inciso')->nullable();
            $table->boolean('condicion')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sanciones');
    }
}
