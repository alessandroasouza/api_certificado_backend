<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    protected $table = 'modelo';

    protected $fillable = [
        'descricao_layout', 'titulo', 'url_back', 'cidade_layout', 'cor_nome', 'cor_titulo','cor_texto'];

    
}
