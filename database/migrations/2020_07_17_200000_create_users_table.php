<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idpersona')->unsigned();
            $table->foreign('idpersona')->references('id')->on('personas');
            $table->integer('idrol')->unsigned();
            $table->foreign('idrol')->references('id')->on('roles');
            $table->string('usuario',40)->unique();
            $table->string('password',50);
            $table->boolean('condicion')->default(1);          
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
