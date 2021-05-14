<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Eventos extends Model
{
    protected $table = 'eventos';

 //   protected $fillable = [
   //     'descricao', 'nota', 'inicio', 'id_usuario', 'ativo', 'data_inicio','img','carga_horaria'
    //];

    public function inscricoes(){
        return $this->hasMany(Inscricao::class, 'id_evento', 'id');
    }
}
