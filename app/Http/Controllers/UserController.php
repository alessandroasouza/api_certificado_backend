<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use  App\Models\User;
use Illuminate\Support\Facades\Auth;

/**
 * Class SubscriptionController
 * @package App\Http\Controllers
 */
class UserController   extends Controller{

    public function __construct(){
     $this->middleware('auth');
    
    }

    public function perfil(){
      return response()->json(['user' => Auth::user()], 200);
    }

    public function index(){
        return response()->json(['users' =>  User::all()], 200);
    }

    public function show($id){
        try {
            $user = User::findOrFail($id);
            return response()->json(['user' => $user], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Usuário não encontrado!'], 404);
        }

    }

    public function destroy(Request $request){
         $id     = $request->id;
         $user   = User::find($id);
        if (!$user->delete()){
             return response()->json(['message' => 'Unauthorized'], 401);
         }

         return response()->json(['message' => 'true']);

     }

     public function update(Request $request){
         $user = User::find($request->id);
         
         if ($request->filled('nome')) {
            $user->nome = $request->nome;
          }

          if ($request->filled('email')) {
            $user->email = $request->email;
          } 
          
          if ($request->filled('apelido')) {
            $user->apelido = $request->apelido;
          } 
         
          if ($request->filled('tipo_usuario')) {
            $user->tipo_usuario = $request->tipo_usuario;
           } 
         
          if ($request->filled('celular')) {
            $user->celular = $request->celular;
           } 
         
         if ($request->filled('documento')) {
            $user->documento = $request->documento;
          } 
          
          if ($request->filled('campus')) {
            $user->campus = $request->campus;
          } 

          if ($request->filled('password')) {
            $plain_Password = $request->password;
            $user->password = app('hash')->make($plain_Password);
          } 
        
        
 
         if (!$user->save()){
             return response()->json(['message' => 'Unauthorized'], 401);
         }
 
         return response()->json(['message' => 'true']);
     }
}

    

