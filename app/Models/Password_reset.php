<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Password_reset extends Model
{
    protected $table = 'password_reset';

    protected $fillable = [
        'token', 'nota', 'inicio', 'id_usuario', 'ativo', 'data_inicio'
    ];
}
