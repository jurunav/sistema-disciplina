<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDemeritosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demeritos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cadete_id')->index();
            $table->unsignedInteger('sancion_id');
            $table->unsignedInteger('sancionador_id');
            $table->string('cant_dia')->nullable();
            $table->string('num_orden')->nullable();
            $table->unsignedInteger('encargado_id');
            $table->timestamps();

            $table->foreign('cadete_id')->references('id')->on('cadetes')->onDelete('cascade');
            $table->foreign('sancion_id')->references('id')->on('sanciones')->onDelete('cascade');
            $table->foreign('sancionador_id')->references('id')->on('personas');
            $table->foreign('encargado_id')->references('id')->on('encargados');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('demeritos');
    }
}
