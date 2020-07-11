<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personas', function (Blueprint $table) {

            $table->increments('id');
            $table->string('grado', 20);
            $table->string('nombre', 250)->unique();
            $table->string('ci', 20);
            $table->string('cm', 20)->nullable();
            $table->string('domicilio', 150)->nullable();
            $table->string('celular', 20)->nullable();
            $table->string('email', 100)->nullable();
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
        Schema::dropIfExists('personas');
    }
}
