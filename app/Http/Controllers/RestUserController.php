<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use App\Party;

class RestUserController extends Controller
{
    public function login(Request $request){
    	$user = User::where('email', $request->email)
               ->first();
    	if($user != null && Hash::check($request->password, $user->password)){
            $user_obj = User::find($user->id);
            if($user_obj->role == 1){
                $doctor_info =  $user_obj->doctor;
            }
            if($user_obj->role == 3){
                $patient_info =  $user_obj->patient;
            }
    		return response()
				->json(['status' => '200',
						'message' => 'Ok',
                        'user'=>$user_obj]); 
    	}else{
    		return response()
				->json(['status' => '401',
						'message' => 'credenciales no validas']); 
    	}
    }

    public function register(Request $request){
    	return "register";	
    	$email_exists = DB::table('users')
                    ->where('email',$request->email)
                    ->first();
        if ($email_exists){
			return response()
				->json(['status' => '406',
						'message' => 'Este email ya ha sido registrado']);  
	    }else{
	        $user = New User;
	        $user->name = $request->name;
	        $user->email = $request->email;
	        $user->password = bcrypt($request->password);
	        $user->save();

	        return response()
				->json(['status' => '201',
						'message' => 'Ok']); 
    	}
    }

    public function resetPass(Request $request){
    	$user = User::where('email', $request->email)
               ->first();
    	if($user != null ){
    		$user = User::find($user->id);   
    		$user->password =  bcrypt($request->password);
    		$user->save();
    		return response()
				->json(['status' => '200',
						'message' => 'Ok']);
    	}else{
    		return response()
				->json(['status' => '406',
						'message' => 'No existe un usuario registrado para ese correo']);
    	}	
    }

    public function createParty(Request $request){
    	$party = Party;
    	$party->name = $request->name;
    	$party->host_user_id = $request->host_user_id;
    	$party->save();
    }
}
