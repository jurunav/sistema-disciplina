<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeIntToDatetimeFromCadetesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cadetes', function (Blueprint $table) {
            \Illuminate\Support\Facades\DB::statement("
                    ALTER TABLE cadetes MODIFY year_ingreso TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cadetes', function (Blueprint $table) {
            \Illuminate\Support\Facades\DB::statement("
                    ALTER TABLE cadetes MODIFY year_ingreso INT(11) NULL");
        });
    }
}
