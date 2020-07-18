<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAutoIncrementToCadetes extends Migration
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
                    ALTER TABLE cadetes MODIFY id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY;");
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
            $table->dropPrimary('id');
            \Illuminate\Support\Facades\DB::statement("
                    ALTER TABLE cadetes MODIFY id INT UNSIGNED NOT NULL;");
        });
    }
}
