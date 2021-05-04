<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use  App\Models\User;
use  App\Models\Access_tokens;
use  App\Models\Password_reset;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Collection as Collection;
use Illuminate\Support\Facades\Validator;
use App\Exceptions;

/**
 * Class SubscriptionController
 * @package App\Http\Controllers
 */
class AuthController  extends BaseController
{
    public function store(Request $request)
    {
        //validate incoming request 
        $this->validate($request, [
            'nome' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'min:6|required_with:password_confirmation|same:confirm',
           'confirm' => 'min:6'
        ]);

       

            $user = new User;
            $user->nome = $request->nome;
            $user->email = $request->email;
            $user->apelido = $request->apelido;
            $user->tipo_usuario = $request->tipo_usuario;
            $user->celular = $request->celular;
            $user->documento = $request->documento;
            $user->campus = $request->campus;
            $plain_Password = $request->password;
            $user->password = app('hash')->make($plain_Password);
            $user->save();
            return response()->json(['user' => $user, 'message' => 'CREATED'], 201);
        }

    public function login(Request $request)
    {
          //validate incoming request 
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['email', 'password']);

        if (! $token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $id = Auth::id();
        $register_token = $this->respondWithToken($token); 
        
        
        $this->login_register($register_token,$id);
        
        return $register_token;
    }

    protected function login_register($register,$id)
    {
        
            $access_tokens             = new Access_tokens;
            $access_tokens->token      =  $register->getData()->token;
            $access_tokens->expire_at  =  $register->getData()->expires_in;
            $access_tokens->user_id    = $id;
            $access_tokens->save();
            return  $access_tokens;
    }
        
    
    protected function respondWithToken($token){
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60,
            'id_user'    => Auth::id()
        ], 200);
    }

    
    protected function savepass($email,$token,$id_user){
           
            $password_reset             =  new Password_reset;
            $password_reset->token      =  $token;
            $password_reset->email      =  $email;
            $password_reset->user_id    =  $id_user;
            $password_reset->save();
            return  $password_reset;

        
    }
    
    public function validatetoken(Request $request){
        
        $token_data = $request->token;
        
        if ( $data = Password_reset::where('token', $token_data)->first() ){
            $id = $data->user_id;
            return response()->json(['message' => 'true']); 
            
        }
        else{
            return response()->json(['message' => 'Código não encontrado'], 401);  
        }
    }
    
    public function resetPassword(Request $request){
        $new_pass = $request->token;
        
        $user->password = $new_pass;
        
        if ($user->update() ){
          
            DB::table('password_reset')->where('email', $user->email)->delete();
          
          return response()->json(['message' => 'true']); 
          
          
          $data    = array('name'=>$name,
                'password'=>$token,
                'msg'=>'Senha Alterada com Sucesso'
            );
          
          Mail::send('mail', $data, function ($message) use ($email) {
            $message->to($email, 'Recuperação de Senha')->subject
                ('Recuperação de Senha');
            $message->from('certificadoftc@gmail.com','FTC - Sistema Certificado');
            });
        }
         else{
            return response()->json(['message' => 'Código não encontrado'], 401);  
        }
    }
    
    
    public function reset(Request $request){
        $email    = $request->email;
        $user     = User::where('email',$email)->first();
        
        
      
        if($user) {
            $id_user  = $user->id;
            $token    = Str::random(10);
            $name     =  $user->nome;
            $reset    = $this->savepass($email,$token,$id_user); 
           
           
            if($reset){
               
                $data    = array('name'=>$name,
                'password'=>$token
               );

                    Mail::send('mail', $data, function ($message) use ($email) {
                    $message->to($email, 'Recuperação de Senha')->subject
                        ('Recuperação de Senha');
                    $message->from('certificadoftc@gmail.com','FTC - Sistema Certificado');
                    });
              
             return response()->json(['message' => 'true']);      
            }
            
            
         } 
        else 
        {
         return response()->json(['message' => 'usuário não encontrado'], 401);
        }


        
       

      
    }
}
