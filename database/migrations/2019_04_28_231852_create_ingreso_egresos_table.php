<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIngresoEgresosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingreso_egresos', function (Blueprint $table) {
            $table->increments('id');
            // $table->integer('idCuenta')->unsigned();
            $table->dateTime('fecha');
            $table->decimal('monto',11,2);
            $table->string('descripcion',200)->nullable();
            $table->integer('tipo');
            $table->boolean('estado')->default(1);
            // $table->foreign('idCuenta')->references('id')->on('cuentas');
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
        Schema::dropIfExists('ingreso_egresos');
    }
}
