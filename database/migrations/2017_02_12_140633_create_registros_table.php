<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegistrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registros', function (Blueprint $table) {
            $table->increments('id');
            $table->string('caso', 20)->unique();
            $table->date('fecha');
            // $table->string('denunciante', 50);
            // $table->string('denunciado', 50);
            // $table->enum('estado', ['CAUTELAR', 'IMPUTADO', 'INICIADO', 'OBSERVADO', 'DESESTIMADO']);
            $table->enum('situacion_procesal', ['APR', 'DIS', 'LIB']);
            $table->text('observaciones')->nullable();
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
        Schema::dropIfExists('registros');
    }
}
