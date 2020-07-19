<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCadetesMeritosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cadetes_meritos', function (Blueprint $table) {
            $table->unsignedInteger('cadete_id')->index();
            $table->foreign('cadete_id')->references('id')->on('cadetes')->onDelete('cascade');

            $table->unsignedInteger('merito_id')->index();
            $table->foreign('merito_id')->references('id')->on('meritos')->onDelete('cascade');
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
        Schema::dropIfExists('cadetes_meritos');
    }
}
