<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use  App\Models\Eventos;

class EventosController extends Controller
{
    public function index(){
        return response()->json(['eventos' =>  Eventos::all()], 200);
    }
}
