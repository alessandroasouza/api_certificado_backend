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
        Schema::table('Inscricao', function (Blueprint $table) {
            DB::statement('ALTER TABLE Inscricao ADD COLUMN IF NOT EXISTS data_chamada1 datetime NOT NULL DEFAULT now();');
            DB::statement('ALTER TABLE Inscricao ADD COLUMN IF NOT EXISTS data_chamada2 datetime NOT NULL DEFAULT now();');
            DB::statement('ALTER TABLE Inscricao ADD COLUMN IF NOT EXISTS data_presenca1 datetime NOT NULL DEFAULT now();');
            DB::statement('ALTER TABLE Inscricao ADD COLUMN IF NOT EXISTS data_presenca2 datetime NOT NULL DEFAULT now();');
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
