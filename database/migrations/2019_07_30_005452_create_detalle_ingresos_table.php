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
            $table->bigIncrements('id');
            $table->unsignedBigInteger('ingreso_id');
            $table->unsignedBigInteger('cuenta_id');
            $table->integer('orden');
            $table->decimal('debe',11,2)->nullable();
            $table->decimal('haber',11,2)->nullable();
            $table->string('descripcion')->nullable();
            $table->boolean('estado')->default(1);
            // $table->primary(['idIE','cuenta_id']);
            $table->foreign('ingreso_id')->references('id')->on('ingresos');
            $table->foreign('cuenta_id')->references('id')->on('cuentas');
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
