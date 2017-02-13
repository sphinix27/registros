<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDelitosTable extends Migration
{
/**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delitos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('articulo')->unsigned();
            $table->string('inciso', 15)->nullable();
            $table->string('nombre');
            $table->timestamps();
        });

        Schema::create('delito_registro', function (Blueprint $table) {
            $table->integer('registro_id');
            $table->integer('delito_id');
            $table->primary(['registro_id', 'delito_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delitos');
        
        Schema::dropIfExists('delito_registro');
    }
}