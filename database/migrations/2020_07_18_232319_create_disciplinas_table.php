<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDisciplinasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disciplinas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->unsignedInteger('premio_id')->index();
            $table->unsignedInteger('categoria_id')->nullable();
            $table->integer('puntaje');
            $table->timestamps();


            $table->foreign('premio_id')->references('id')->on('premios')->onDelete('cascade');
            $table->foreign('categoria_id')->references('id')->on('categorias')->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('disciplinas');
    }
}
