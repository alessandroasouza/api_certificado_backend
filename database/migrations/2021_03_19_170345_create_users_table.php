<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('nome',50);
            $table->string('apelido',50);
            $table->string('email',50)->unique()->notNullable();
            $table->string('password');
            $table->string('tipo_usuario',15);
            $table->string('celular',15);
            $table->string('documento',25);
            $table->string('campus',40);
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
        Schema::dropIfExists('users');
    }
}
