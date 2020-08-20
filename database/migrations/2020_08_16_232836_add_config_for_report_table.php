<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddConfigForReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sanciones', function (Blueprint $table) {
            $table->boolean('salida_franco')->default(true)->nullable();
            $table->boolean('por_orden')->default(false)->nullable();
            $table->boolean('por_reposo')->default(false)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sanciones', function (Blueprint $table) {
            $table->dropColumn('salida_franco');
            $table->dropColumn('por_orden');
            $table->dropColumn('por_reposo');
        });
    }
}
