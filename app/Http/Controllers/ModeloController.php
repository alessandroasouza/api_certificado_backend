<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ModeloController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        $evento = Modelo::all();
        return response()->json(['message' => $evento], 200);
    }

    
    public function destroy(Request $request){
        $id     = $request->id;
        $modelo = Modelo::find($id);
      
        if (!$modelo->delete()){
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json(['message' => 'true']);

    }


    public function update(Request $request){
       
        $id  = $request->id;
        
        $modelo = Modelo::find($id);

        
        $data = array_filter($request->all(), function($item){
          return !empty($item[0]);
        });
        $modelo->update($data);
        $modelo->save();
        
        return response()->json($data);
     }

     public function show($id){
       
        $modelo =  Modelo::find($id);
        return response()->json(['modelo' => $modelo], 200);
    }

    public function store(Request $request){
            
        $modelo = new Modelo;
        $modelo->descricao_layout       = $request->descricao_layout;
        $modelo->titulo                 = $request->titulo;
        $modelo->url_back               = $request->url_back;
        $modelo->cidade_layout          = $request->cidade_layout;
        $modelo->cor_nome               = $request->cor_nome;
        $modelo->cor_titulo             = $request->cor_titulo;
        $modelo->cor_texto              = $request->cor_texto;
        
        
        $modelo->save();
        return response()->json(['modelo' => $modelo, 'message' => 'CREATED'], 201);
    }

}