<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
        'lib_presenca_2',
        'data_chamada1',
        'data_chamada2',
        'data_presenca1',
        'data_presenca2'

    ];

       /**/
    public function users(){
        return $this->hasOne(User::class, $foreignKey = 'id', $localKey = 'id_usuario');
    }
     
    public function evento(){
        return $this->hasOne(Eventos::class, $foreignKey = 'id_evento', $localKey = 'id');
    }

     public function listuserpalestrante($id){
          
        $str ="SELECT i.*,e.descricao,e.nota,a.nome,s.nome as palestrante,".
            " e.carga_horaria,e.data_inicio,e.inicio".
            " FROM inscricao i ".
            " INNER JOIN users a on (a.id = i.id_usuario)".
            " inner join eventos e on (e.id=i.id_evento)".
            " inner join users s on (s.id=e.id_usuario)".
            "where s.id=$id";

           // $result = DB::select($str);
           $result = 'sandro' ;
           return $result;    

     }
}
