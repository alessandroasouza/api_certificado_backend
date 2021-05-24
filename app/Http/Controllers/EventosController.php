<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use  App\Models\Eventos;
use App\Models\Inscricao;
use Carbon\Carbon;

class EventosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(){
        $evento = Eventos::all();
        return response()->json(['message' => $evento], 200);
    }

    public function show($id){
       
            $evento = Eventos::find($id);
            return response()->json(['evento' => $evento], 200);
    }
   
    public function destroy(Request $request){
        $id     = $request->id;
        
       
        $inscricao = Inscricao::all()->where('id_evento', $id);
       
        if (!$inscricao->isEmpty()) { 
            return response()->json(['message' => 'Este evento está sendo usado numa inscrição'], 401);
        } else 
         
        $evento   = Eventos::find($id);
      
        if (!$evento->delete()){
            return response()->json(['message' => 'Não autorizado'], 401);
        }

        return response()->json(['message' => 'true']);

    }
    
    public function update(Request $request){
        
        $id  = $request->id;
        
        $evento = Eventos::find($request->id);
        
        $data = array_filter($request->all(), function($item){
          return !empty($item[0]);
        });
        
        $evento->ativo         = $request->ativo; 
        $evento->carga_horaria = $request->carga_horaria; 
        
        if(!empty($request->input('ativo'))) {
            $evento->ativo = $request->ativo; 
        } else {
            $evento->ativo = $evento->ativo;
        }
        
        
        $evento->update($data);
        $evento->save();
        
        return response()->json($evento);
    }
  
    
    public function store(Request $request){
            
            $date  = Carbon::now();
            $date1 = Carbon::createFromFormat('Y-m-d', $request->data_inicio);
        
           
             if ( strtotime($date1) < strtotime($date)  ){
                return response()->json(['message' => 'Data do evento menor que atual'], 401); 
             }
            //$value = $date2->diffInHours($date1); 
        
            $evento = new Eventos;
            $evento->descricao       = $request->descricao;
            $evento->id_usuario      = $request->id_usuario;
            $evento->nota            = $request->nota;
            $evento->data_inicio     = date('Y-m-d', strtotime($request->data_inicio));
            $evento->inicio          = $request->inicio;
            $evento->ativo           = $request->ativo;
            $evento->carga_horaria   = $request->carga_horaria;
            $evento->img             = $request->img;
            
            $evento->save();
            return response()->json(['user' => $evento, 'message' => 'CREATED'], 201);
        }
   
}
