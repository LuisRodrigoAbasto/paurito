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
           $table->unsignedBigInteger('egreso_id');
           $table->unsignedBigInteger('cuenta_id');
            $table->integer('orden');
            $table->decimal('debe',11,2)->nullable();
            $table->decimal('haber',11,2)->nullable();
            $table->string('descripcion')->nullable();
            $table->boolean('estado')->default(1);
            // $table->primary(['idIE','idCuenta']);
            $table->foreign('egreso_id')->references('id')->on('egresos');
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
        Schema::dropIfExists('detalle_egresos');
    }
}
