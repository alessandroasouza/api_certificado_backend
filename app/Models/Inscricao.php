<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inscricao extends Model
{
    protected $table = 'inscricao';

    protected $fillable = [
        'id_usuario', 
        'id_evento', 
        'presenca_1', 
        'presenca_2', 
        'campus', 
        'semestre',
        'certificado',
        'lib_presenca_1',
        'lib_presenca_2'
    ];

       /**/
    public function users(){
        return $this->hasOne(User::class, $foreignKey = 'id', $localKey = 'id_usuario');
    }
     
    public function evento(){
        return $this->hasOne(Eventos::class, $foreignKey = 'id_evento', $localKey = 'id');
    }
}
