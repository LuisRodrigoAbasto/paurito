<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleComprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_compras', function (Blueprint $table) {
            $table->integer('idCompra')->unsigned();
            $table->integer('idProducto')->unsigned();
            $table->decimal('cantidad',11,2);
            $table->decimal('precio',11,2);
            $table->boolean('estado')->default(1);
            $table->primary(['idCompra','idProducto']);
            $table->foreign('idCompra')->references('id')->on('compras');
            $table->foreign('idProducto')->references('id')->on('productos');
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
        Schema::dropIfExists('detalle_compras');
    }
}
