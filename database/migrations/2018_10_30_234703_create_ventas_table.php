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
            $table->bigIncrements('id');
            $table->integer('factura');
            $table->integer('registro');
            $table->unsignedBigInteger('cliente_id');
            $table->Biginteger('formula_id');
            $table->dateTime('fecha');
            $table->integer('pago');
            $table->decimal('cantidad',11,2);
            $table->decimal('monto_total',11,2);
            $table->string('descripcion')->nullable();
            $table->string('tipo',10)->default('ventas');
            $table->boolean('estado')->default(1);
            $table->foreign('cliente_id')->references('id')->on('cuentas');
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
