<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inscricao extends Model
{
    protected $table = 'inscricao';

    protected $fillable = [
        'id_usuario', 'id_evento', 'presenca_1', 'presenca_2', 'certificado'
    ];
}
