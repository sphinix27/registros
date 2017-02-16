<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->timestamps();
        });

        Schema::create('denunciados', function (Blueprint $table) {
            $table->unsignedInteger('registro_id');
            $table->unsignedInteger('persona_id');
            $table->timestamps();
            // $table->primary(['registro_id', 'persona_id']);
        });

        Schema::create('denunciantes', function (Blueprint $table) {
            $table->unsignedInteger('registro_id');
            $table->string('persona_id');
            $table->timestamps();
            // $table->primary(['registro_id', 'persona_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personas');
        Schema::dropIfExists('denunciados');
        Schema::dropIfExists('denunciantes');
    }
}
