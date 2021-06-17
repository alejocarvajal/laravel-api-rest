<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiccionariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diccionario', function (Blueprint $table) {
            $table->increments("id");
            $table->string("departamento");
            $table->string("localidad");
            $table->string("municipio");
            $table->string("nombre");
            $table->integer("anios_activo");
            $table->string("tipo_persona");
            $table->string("tipo_cargo");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('diccionario');
    }
}
