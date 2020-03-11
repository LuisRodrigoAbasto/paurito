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
            $table->bigIncrements('id');
            $table->unsignedBigInteger('formula_id');
            $table->unsignedBigInteger('producto_id');
            $table->integer('orden');
            $table->decimal('cantidad',11,2);
            $table->boolean('estado')->default(1);
            // $table->primary(['idFormula','idProducto']);
            $table->foreign('formula_id')->references('id')->on('formulas');
            $table->foreign('producto_id')->references('id')->on('productos');
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
