<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Eventos extends Model
{
    protected $table = 'eventos';

    protected $fillable = [
     'descricao','id_usuario', 'nota','data_inicio','inicio','ativo','carga_horaria','img'
    ];

    public function inscricoes(){
        return $this->hasMany(Inscricao::class, 'id_evento', 'id');
    }
}
