<?php

namespace App\Http\Controllers;

use App\Models\Eventos;
use App\Models\Inscricao;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class InscricaoController extends Controller
{
    
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        return response()->json(['inscricao' => Inscricao::all()], 200);
    }

    public function show($id){
        try {
            $evento = Inscricao::find($id);
            return response()->json(['evento' => $evento], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Inscrição não encontrado!'], 404);
        }
    }

    public function details($id){
        $inscricao = Inscricao::all()->where('id_usuario', $id);

        if(!$inscricao){
            return response()->json([
                'error' => 'Dados não encontrados'
            ], 404);
        }

        return response()->json($inscricao);
    }

    public function listfulluser($id){
        $inscricoes = Eventos::where('id', $id)->with('inscricoes', 'inscricoes.users')->get();
        return response()->json($inscricoes);
    }

    public function store(Request $request){
        try{
            $inscricao = new Inscricao;
            $inscricao->id_usuario  = $request->id_usuario;
            $inscricao->id_evento   = $request->id_evento;
            $inscricao->presenca_1  = $request->presenca_1;
            $inscricao->presenca_2  = $request->presenca_2;
            $inscricao->campus      = $request->campus;
            $inscricao->semestre    = $request->semestre;
            $inscricao->certificado = $request->certificado;

            $inscricao->save();
            return response()->json([
                'inscricao' => $inscricao,
                'message'   => 'CREATED'], 200);
        }catch(Exception $e){
            return response()->json(['message' => 'Inscrição Registration Failed!'], 409);
        }
    }

    public function update(Request $request){
        $inscricao = Inscricao::find($request->id);
        $data = array_filter($request->all(), function($item){
          return !empty($item[0]);
        });

        $inscricao->fill($data);
        $inscricao->save();

        return response()->json($inscricao);
     }
  
     public function destroy(Request $request){
        $id     = $request->id;
        $inscricao   = Inscricao::find($id);
      
        if (!$inscricao->delete()){
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json(['message' => 'true']);

    }
}
