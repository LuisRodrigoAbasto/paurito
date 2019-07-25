<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleIngresosEgresosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_ingresos_egresos', function (Blueprint $table) {
            $table->integer('idIE')->unsigned();
            $table->integer('idCuenta')->unsigned();
            $table->decimal('debe',11,2);
            $table->decimal('haber',11,2);
            $table->string('descripcionD',200)->nullable();
            $table->boolean('estado')->default(1);
            $table->primary(['idIE','idCuenta']);
            $table->foreign('idIE')->references('id')->on('ingreso_egresos');
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
        Schema::dropIfExists('detalle_ingresos_egresos');
    }
}
