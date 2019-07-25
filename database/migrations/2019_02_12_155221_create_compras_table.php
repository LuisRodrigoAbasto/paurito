<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compras', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idProveedor')->unsigned();
            $table->dateTime('fecha');
            $table->integer('pago');
            $table->decimal('montoCompra',11,2);
            $table->string('descripcion',200)->nullable();
            $table->boolean('estado')->default(1);
            $table->foreign('idProveedor')->references('id')->on('cuentas');
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
        Schema::dropIfExists('compras');
    }
}
