<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use  App\Models\Eventos;

class EventosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(){
        return response()->json(['eventos' =>  Eventos::all()], 200);
    }

    public function show($id){
        try {
            $evento = Eventos::find($id);
            return response()->json(['evento' => $evento], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Evento  não encontrado!'], 404);
        }

    }
   
    public function destroy(Request $request){
        $id     = $request->id;
        $evento   = Eventos::find($id);
      
        if (!$evento->delete()){
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json(['message' => 'true']);

    }
    
    public function update(Request $request){
        $evento = Eventos::find($request->id);
        $request['data_inicio'] = date('Y-m-d', strtotime($request->data_inicio));
        $data = array_filter($request->all(), function($item){
          return !empty($item[0]);
        });

        $evento->fill($data);
        $evento->save();

        return response()->json($evento);
     }
  
    
    public function store(Request $request)
    {
        try {
            $evento = new Eventos;
            $evento->descricao  = $request->descricao;
            $evento->id_usuario = $request->id_usuario;
            $evento->nota       = $request->nota;
            $evento->data_inicio = date('Y-m-d', strtotime($request->data_inicio));
            $evento->inicio     = $request->inicio;
            $evento->ativo      = $request->ativo;

            $evento->save();
            return response()->json(['user' => $evento, 'message' => 'CREATED'], 201);

        } catch (\Exception $e) {
            //return error message
           // die($e);
            return response()->json([ 'message' => 'Eventos Registration Failed!'], 409);
        }

    }
   
}
