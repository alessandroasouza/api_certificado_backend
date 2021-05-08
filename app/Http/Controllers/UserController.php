<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use  App\Models\User;
use  App\Models\Access_tokens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
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
       $user = User::find($id);
        return response()->json(['user' => $user], 200);
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

     public function update(Request $request){
        //$data = User::find($id);
        //$data->update($request->all());
        /**/
        $id  = $request->id;
        
        $user = User::find($id);

        
        $data = array_filter($request->all(), function($item){
          return !empty($item[0]);
        });
        
        $user->update($data);
        $user->save();
        
        return response()->json($data);
     }

     public function updatepassword(Request $request){
        
      $this->validate($request, [
         'password' => 'min:6|required_with:password_confirmation|same:confirm',
         'confirm' => 'min:6'
      ]);
      
      $new_pass       = $request->password;
      $user_id        = $request->id;
      $new_pass       = app('hash')->make($new_pass); 
     
      $user = User::find($user_id);
      $email = $user->email; 

      if (User::where('id', $user->id)->update(['password' => $new_pass ])) {
          
          $data    = array('name'=>$user->nome,
             'msg'=>'Senha Alterada com Sucesso'
          );
        
        Mail::send('mail_confirm', $data, function ($message) use ($email) {
          $message->to($email, 'Recuperação de Senha')->subject
              ('Recuperação de Senha');
          $message->from('certificadoftc@gmail.com','FTC - Sistema Certificado');
          });

          return response()->json(['message' => 'true']); 
      }
       else{
          return response()->json(['message' => 'erro na atualização'], 401);  
      }
  }

}

    

