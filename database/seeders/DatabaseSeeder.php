<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'nome'     => 'Master',
            'email'    => 'master@certicado.local',
            'apelido' => 'master',
            'tipo_usuario'     => 'master',
            'celular' =>'7599999999',
            'documento' =>'000000000000',
            'campus' =>'Fsa',
            'password' =>  Hash::make('123qwe'),
        ]);

        DB::table('users')->insert([
            'nome' => Str::random(10),
            'email' => Str::random(10).'@gmail.com',
            'apelido' => Str::random(10),
            'tipo_usuario'     => 'aluno',
            'celular' => Str::random(10),
            'documento' => Str::random(10),
            'campus' =>'Fsa',
            'password' => Hash::make('123qwe'),

        ]);

       
    }
}
