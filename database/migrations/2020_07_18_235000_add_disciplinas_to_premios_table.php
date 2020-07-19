<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDisciplinasToPremiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('premios', function (Blueprint $table) {
            $table->dropForeign(['idcategoria']);
            $table->dropIndex('premios_idcategoria_foreign');
            $table->dropColumn('idcategoria');

            $table->unsignedInteger('disciplina_id')->index();
            $table->foreign('disciplina_id')->references('id')->on('disciplinas')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('premios', function (Blueprint $table) {
            $table->dropForeign(['disciplina_id']);
            $table->dropIndex(['disciplina_id']);
            $table->dropColumn('disciplina_id');

            $table->unsignedInteger('idcategoria');
            $table->foreign('idcategoria')->references('id')->on('categorias');
            $table->index('idcategoria','premios_idcategoria_foreign');
        });
    }
}
