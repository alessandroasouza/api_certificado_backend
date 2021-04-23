<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use  App\Models\User;
use  App\Models\Access_tokens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            $user = User::find($id);
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

     public function logout(Request $request) {
         $id     = $request->id_token;
         $affectedRows = Access_tokens::where('token', '=', $id)->delete();
         
         if ($affectedRows ==0){
             return response()->json(['message' => 'Unauthorized'], 401);
         }

         return response()->json(['message' => 'true']);
         Auth::logout();
       }

     public function update(Request $request, $id){
        //$data = User::find($id);
        //$data->update($request->all());
        /**/
        $user = User::find($id);

        
        $data = array_filter($request->all(), function($item){
          return !empty($item[0]);
        });
        
        $user->update($data);
        $user->save();
        
        return response()->json($data);
     }
}

    

