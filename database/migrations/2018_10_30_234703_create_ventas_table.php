<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('factura');
            $table->integer('registro');
            $table->integer('idCliente')->unsigned();
            $table->integer('idFormula');
            $table->dateTime('fecha');
            $table->integer('pago');
            $table->decimal('cantidad',11,2);
            $table->decimal('montoVenta',11,2);
            $table->string('descripcion',200)->nullable();
            $table->string('tipo',20)->nullable();
            $table->boolean('estado')->default(1);
            $table->foreign('idCliente')->references('id')->on('cuentas');
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
        Schema::dropIfExists('ventas');
    }
}
