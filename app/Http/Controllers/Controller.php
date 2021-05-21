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
            'expires_in' => Auth::factory()->getTTL() * 60
        ], 200);
    }

    public function checkform(Request $request,$rules,$fields=[] ){
        $messages = include './../resources/lang/pt/validation.php';
        
        

        $validator = Validator::make($request->all(),
            $rules, 
            $messages['validations'],
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
