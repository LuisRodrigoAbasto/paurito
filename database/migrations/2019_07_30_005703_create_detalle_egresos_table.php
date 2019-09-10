<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleEgresosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_egresos', function (Blueprint $table) {
            $table->bigIncrements('id');
           $table->unsignedBigInteger('idEgreso');
           $table->unsignedBigInteger('idCuenta');
            $table->integer('orden');
            $table->decimal('debe',11,2)->nullable();
            $table->decimal('haber',11,2)->nullable();
            $table->string('descripcionD',200)->nullable();
            $table->boolean('estado')->default(1);
            // $table->primary(['idIE','idCuenta']);
            $table->foreign('idEgreso')->references('id')->on('egresos');
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
        Schema::dropIfExists('detalle_egresos');
    }
}
