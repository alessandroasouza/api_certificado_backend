<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use  Illuminate\Support\Facades\Validator; 

class Controller extends BaseController
{
    protected function respondWithToken($token)
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 180
        ], 200);
    }

    public function validaCPF($cpf) {
 
        // Extrai somente os números
        $cpf = preg_replace( '/[^0-9]/is', '', $cpf );
         
        // Verifica se foi informado todos os digitos corretamente
        if (strlen($cpf) != 11) {
            return response()->json(['message' => 'Cpf invalido'], 401);
        }
    
        // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return response()->json(['message' => 'Cpf invalido'], 401);
        }
    
        // Faz o calculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return response()->json(['message' => 'Cpf invalido'], 401);
            }
        }
        
        //return true;
    
    }
    
    
    public function checkform(Request $request,$rules,$fields=[] ){
        $messages = include './../resources/lang/pt/validation.php';
       

        $validator = Validator::make($request->all(),
            $rules, 
            $messages,
            $fields
          );  
         
         
          
         if ($validator->fails()) {
            
            $errors = $validator->errors();

            return  response()->json([
                'success' => false,
                 'status' => 0,
                 'message' => 'texto',
                 'errors'=> $errors->all(),
                ]);

                
            } 
    }
}
