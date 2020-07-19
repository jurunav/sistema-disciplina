<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeritosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meritos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('num_orden')->nullable();
            $table->unsignedInteger('premio_id');
            $table->string('descripcion')->nullable();
            $table->unsignedInteger('encargado_id')->nullable();
            $table->timestamps();

            $table->foreign('premio_id')->references('id')->on('premios')->onDelete('cascade');
            $table->foreign('encargado_id')->references('id')->on('encargados')->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meritos');
    }
}
