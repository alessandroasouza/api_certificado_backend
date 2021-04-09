<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTokenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('access_tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token',500);
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('expire_at')->nullable();
            $table->timestamps();
        });

        DB::statement("create unique index access_tokens_token_uindex on access_tokens (token);");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('token');
        DB::statement("DROP INDEX access_tokens_token_uindex");
        DB::statement("DROP TABLE access_tokens");
    }
}
