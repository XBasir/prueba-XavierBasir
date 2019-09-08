<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Empleados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombres');
            $table->string('apellido_paterno_')->nullable();
            $table->string('apellido_materno')->nullable();
            $table->string('rfc', 30)->unique()->nullable();
            $table->string('clave_del_ife')->unique()->nullable();
            $table->string('clave_de_elector', 30)->unique()->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('curp', 30)->nullable();
            $table->string('afiliacion_a_imss', 30)->unique()->nullable();
            $table->string('fecha_de_contrato')->nullable();
            $table->string('fecha_de_nacimiento')->nullable();
            $table->unsignedInteger('empresa');
            $table->foreign('empresa')->references('id')->on('empresa');
            $table->unsignedInteger('sexo');
            $table->foreign('sexo')->references('id')->on('sexo');
            $table->unsignedInteger('estado_civil')->nullable();
            $table->string('entidad_de_nacimiento',50)->nullable();
            $table->string('municipio_de_nacimiento', 50)->nullable();
            $table->string('colonia_de_nacimiento_', 50)->nullable();
            $table->string('modo_de_nacionalidad')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empleados');
    }
}
