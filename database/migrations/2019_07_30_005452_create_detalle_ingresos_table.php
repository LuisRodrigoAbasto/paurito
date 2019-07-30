<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleIngresosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_ingresos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idIngreso')->unsigned();
            $table->integer('idCuenta')->unsigned();
            $table->integer('orden');
            $table->decimal('debe',11,2)->nullable();
            $table->decimal('haber',11,2)->nullable();
            $table->string('descripcionD',200)->nullable();
            $table->boolean('estado')->default(1);
            // $table->primary(['idIE','idCuenta']);
            $table->foreign('idIngreso')->references('id')->on('ingreso_egresos');
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
        Schema::dropIfExists('detalle_ingresos');
    }
}
