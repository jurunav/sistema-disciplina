<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeIdColumnTypeFromCadetes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cadetes', function (Blueprint $table) {
            $table->dropForeign(['id']);
            $table->dropIndex(['id']);
            \Illuminate\Support\Facades\DB::statement("
                    ALTER TABLE cadetes MODIFY id INT UNSIGNED");
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
                    ALTER TABLE cadetes MODIFY COLUMN id int(10) unsigned not null");
            $table->foreign('id')->references('id')->on('personas')->onDelete('cascade');
            $table->index('id');
        });
    }
}
