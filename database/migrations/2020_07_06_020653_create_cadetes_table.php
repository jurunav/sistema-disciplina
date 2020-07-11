<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCadetesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cadetes', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->integer('year_ingreso')->nullable();
                       
            $table->foreign('id')->references('id')->on('personas')->onDelete('cascade'); 
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cadetes');
    }
}
