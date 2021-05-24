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
use Carbon\Carbon;

class InscricaoController extends Controller
{
     public function index(){
        return response()->json(['inscricao' => Inscricao::all()], 200);
    }

    public function show($id){
        $evento = Inscricao::find($id);
        return response()->json(['evento' => $evento], 200);
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


    public function listevent($id){
         $list = DB::table('inscricao')
        ->join('users', 'users.id', '=', 'inscricao.id_usuario')    
        ->join('eventos', function ($join) use ($id)  {
                $join->on('inscricao.id_evento', '=', 'eventos.id')
                     ->where('inscricao.id_evento', '=',($id) );
            })
            ->join('users as usup', 'users.id', '=','eventos.id_usuario')     
            ->select('inscricao.*', 'eventos.descricao', 'eventos.nota','users.nome','usup.nome as nome_paletrante','eventos.carga_horaria','eventos.data_inicio','eventos.inicio','img')
            ->get();
           
            if(!$list){
                return response()->json([
                    'error' => 'Dados não encontrados'
                ], 404);
            }
            return response()->json($list); 
    }

    public function listeventusers($id){
        $list = DB::table('inscricao')
       ->join('users', 'users.id', '=', 'inscricao.id_usuario')    
       ->join('eventos', function ($join) use ($id)  {
               $join->on('inscricao.id_evento', '=', 'eventos.id')
                    ->where('inscricao.id_usuario', '=',($id) );
           })
           ->join('users as palestrante', DB::raw( 'palestrante.id' ), '=', 'eventos.id_usuario')    
           ->select('inscricao.*', 'eventos.descricao', 'eventos.nota','users.nome',DB::raw( 'palestrante.nome as nome_palestrante' ),'eventos.carga_horaria','eventos.data_inicio','eventos.inicio','img')
           ->get();
          
           
           if(!$list){
            return response()->json([
                'error' => 'Dados não encontrados'
            ], 404);
        }
           
           return response()->json($list); 
   }
   
   public function listeventteacher($id){
        $list = DB::table('inscricao')
            ->join('users', 'users.id', '=', 'inscricao.id_usuario')    
            ->join('eventos', 'eventos.id', '=', 'inscricao.id_evento')
            ->join('users as palestrante', DB::raw( 'palestrante.id' ), '=', 'eventos.id_usuario')    
            ->where('eventos.id_usuario', $id )
        
            ->select('inscricao.*', 'eventos.descricao', 'eventos.nota','users.nome',DB::raw( 'palestrante.nome as nome_palestrante' ),'eventos.carga_horaria','eventos.data_inicio','eventos.inicio','img')
        ->get();
        
        if(!$list){
            return response()->json([
                'error' => 'Dados não encontrados'
            ], 404);
        }
        return response()->json($list); 
    }  
	


    public function certificateevent(Request $request){
        $id_usuario  = $request->id_usuario;
        $id_evento   = $request->id_evento;
       
        $list = DB::table('inscricao')
       ->join('users', 'users.id', '=', 'inscricao.id_usuario')    
       ->join('eventos', function ($join) use ($id_evento,$id_usuario)  {
               $join->on('inscricao.id_evento', '=', 'eventos.id')
                    ->where('inscricao.id_evento', '=',($id_evento) )
                    ->orWhere(function($query) use ($id_usuario) {
                        $query->where('inscricao.id_usuario', '=', ($id_usuario))
                              ->where('presenca_1', '>', 0)
                              ->where('presenca_2', '>', 0);
                    });
           })
           //->join('eventos', 'users.id', '=', 'eventos.id_usuario')    
           ->select('inscricao.campus','inscricao.semestre', 'eventos.descricao', 'eventos.nota','users.nome',
              'apelido','eventos.data_inicio','eventos.inicio',
            )
           ->get();
          
           
           return response()->json($list); 
   }
    
   
    public function store(Request $request){
            $user = Inscricao::all()->where('id_usuario', $request->id_usuario)->where('id_evento', $request->id_evento)->first();
            
            if($user){
                return response()->json(['message' => 'Usuário Já inscrito no Evento'], 401);   
            }

            
            $evento = Eventos::all()->where('id', $request->id_evento)->first(); 
            
            $ativo = $evento->ativo;
            
            if($ativo==1){
                return response()->json(['message' => 'Evento desativado'], 401);   
            }

            $date  = Carbon::now();
            $date1 = Carbon::createFromFormat('Y-m-d', $evento->data_inicio);
        
           
             if ( strtotime($date1) <= strtotime($date)  ){
                return response()->json(['message' => 'Inscrição encerrada!'], 401); 
             }
            
            $inscricao = new Inscricao;
            $inscricao->id_usuario  = $request->id_usuario;
            $inscricao->id_evento   = $request->id_evento;
            $inscricao->presenca_1  = $request->presenca_1;
            $inscricao->presenca_2  = $request->presenca_2;
            $inscricao->campus      = $request->campus;
            $inscricao->semestre    = $request->semestre;
            $inscricao->certificado = $request->certificado;
            $inscricao->lib_presenca_1 = $request->lib_presenca_1; 
            $inscricao->lib_presenca_2 = $request->lib_presenca_2; 
            
            $inscricao->save();
            return response()->json([
                'inscricao' => $inscricao,
                'message'   => 'CREATED'], 200);
       
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

    public function attendanceone(Request $request){
        $id     = $request->id;
        
        $inscricao   = Inscricao::find($id);

        if ( $inscricao->presenca_1==1){  
            return response()->json(['message' => 'Chamada 1 já respondida'], 401); 
          }
        
         $date = Carbon::now();
        
          
          
         $date1 = Carbon::createFromFormat('Y-m-d H:i:s', $inscricao->data_chamada1);
         $date2 = Carbon::createFromFormat('Y-m-d H:i:s', $date);
         $value = $date2->diffInHours($date1);
         
        

         if ($value>1){
           return response()->json(['message' => 'Não autorizado, você tem apenas o limte de 1h para responder a chamada'], 401);
          }
         
         if (Inscricao::where('id', $id)->where('presenca_1', '0')->where('lib_presenca_1', '1')->update(['presenca_1' => 1,'data_presenca1' => $date])){
                return response()->json(['message' => 'true']);
        }
        
         return response()->json(['message' => 'Não autorizado'], 401);

    }

    public function attendancetwo(Request $request){
        $id     = $request->id;
        $inscricao   = Inscricao::find($id);
        
        if ( $inscricao->presenca_1==0){  
            return response()->json(['message' => 'Chamada 1 ainda não foi respondida'], 401); 
         }

         if ( $inscricao->presenca_2==1){  
            return response()->json(['message' => 'Chamada 2 já respondida'], 401); 
          }
   
          $date = Carbon::now();
          
          
          $date1 = Carbon::createFromFormat('Y-m-d H:i:s', $inscricao->data_chamada2);
          $date2 = Carbon::createFromFormat('Y-m-d H:i:s', $date);
          $value = $date2->diffInHours($date1);
          
         

          if ($value>1){
            return response()->json(['message' => 'Não autorizado, você tem apenas o limte de 1h para responder a chamada'], 401);
           }
          
         
         
          if (Inscricao::where('id', $id)->where('presenca_2', '0')->where('lib_presenca_2', '1')->update(['presenca_2' => 1,'data_presenca2' => $date])){
              return response()->json(['message' => 'true']);
         }
       
          return response()->json(['message' => 'Não autorizado'], 401);
    }

    public function hascertificate(Request $request){
        $id     = $request->id;
        
        if (Inscricao::where('id', $id)->update(['certificado' => 1])){
             return response()->json(['message' => 'true']);
        }
   
         return response()->json(['message' => 'Unauthorized'], 401);

    }


    public function activeattendanceone(Request $request){
        $id     = $request->id;
        
        $inscricao   = $user = DB::table('inscricao')->where('id_evento', $id)->first(); 
        
        $date = Carbon::now();
        
        if (Inscricao::where('id_evento', $id)->update(['lib_presenca_1' => 1,'data_chamada1' => $date])){
             return response()->json(['message' => 'true']);
        }
   
         return response()->json(['message' => 'Unauthorized'], 401);

    }

    public function activeattendancethow(Request $request){
        $id     = $request->id;
        
        $inscricao   = $user = DB::table('inscricao')->where('id_evento', $id)->first(); 
        
       
        
        if ( $inscricao->lib_presenca_1==0){  
            return response()->json(['message' => 'Chamada 1 não foi liberada'], 401);  
         }
        
         $date = Carbon::now();
         if (Inscricao::where('id_evento', $id)->update(['lib_presenca_2' => 1,'data_chamada2' => $date])){
             return response()->json(['message' => 'true']);
        }
   
         return response()->json(['message' => 'Unauthorized'], 401);

    }
}