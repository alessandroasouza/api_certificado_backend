<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModeloTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modelo', function (Blueprint $table) {
            $table->id();
            $table->string('descricao_layout',300);
            $table->string('titulo',60);
            $table->string('url_back',300);
            $table->string('cidade_layout',50);
            $table->string('cor_nome',10);
            $table->string('cor_titulo',10);
            $table->string('cor_texto',10);
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
        Schema::dropIfExists('modelo');
    }
}
