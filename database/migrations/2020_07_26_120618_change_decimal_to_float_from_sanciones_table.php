<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDecimalToFloatFromSancionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sanciones', function (Blueprint $table) {
            \Illuminate\Support\Facades\DB::statement("
                    ALTER TABLE sanciones MODIFY puntaje_dia FLOAT(8,2) NULL");
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
            \Illuminate\Support\Facades\DB::statement("
                    ALTER TABLE sanciones MODIFY puntaje_dia DECIMAL(8,2) NULL");
        });
    }
}
