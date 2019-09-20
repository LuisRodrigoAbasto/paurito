<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCuentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuentas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('idCuenta')->nullable();
            $table->string('nombre',200);
            $table->string('telefono',20)->nullable();
            $table->string('empresa',100)->nullable();
            $table->string('direccion',100)->nullable();
            $table->integer('nivel');
            $table->integer('nivel1');
            $table->integer('nivel2');
            $table->integer('nivel3');
            $table->integer('nivel4');
            $table->integer('nivel5');
            $table->boolean('estado')->default(1);
            $table->foreign('idCuenta')->references('id')->on('cuentas');
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
        Schema::dropIfExists('cuentas');
    }
}
