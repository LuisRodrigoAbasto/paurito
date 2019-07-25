<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleFormulasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_formulas', function (Blueprint $table) {
            $table->integer('idFormula')->unsigned();
            $table->integer('idProducto')->unsigned();
            $table->decimal('cantidad',11,2);
            $table->boolean('estado')->default(1);
            $table->primary(['idFormula','idProducto']);
            $table->foreign('idFormula')->references('id')->on('formulas');
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
        Schema::dropIfExists('detalle_formulas');
    }
}
