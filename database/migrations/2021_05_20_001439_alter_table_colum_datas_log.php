<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableColumDatasLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inscricao', function (Blueprint $table) {
           // DB::statement('ALTER TABLE inscricao ADD COLUMN IF NOT EXISTS data_chamada1 datetime NOT NULL DEFAULT now();');
           // DB::statement('ALTER TABLE inscricao ADD COLUMN IF NOT EXISTS data_chamada2 datetime NOT NULL DEFAULT now();');
           // DB::statement('ALTER TABLE inscricao ADD COLUMN IF NOT EXISTS data_presenca1 datetime NOT NULL DEFAULT now();');
           // DB::statement('ALTER TABLE inscricao ADD COLUMN IF NOT EXISTS data_presenca2 datetime NOT NULL DEFAULT now();');
           $table->date('data_chamada1')->nullable()->default(null);
           $table->date('data_chamada2')->nullable()->default(null);
           $table->date('data_presenca1')->nullable()->default(null);
           $table->date('data_presenca2')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Inscricao', function (Blueprint $table) {
            //
        });
    }
}
