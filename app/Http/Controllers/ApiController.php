<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterAuthRequest;
use App\User;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class ApiController extends Controller
{
    // public $loginAfterSignUp = true;

    public function register(RegisterAuthRequest $request){
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        // if($this->loginAfterSignUp){
        //     return $this->login($request);
        // }

        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'User register succesfully'
        ],200);

    }

    public function login(Request $request){
        $input = $request->only('email','password');
        $jwt_token = null;
        if(!$jwt_token = JWTAuth::attempt($input)){
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password'
            ], 401);
        }
        
        return response()->json([
            'success' => true,
            'token' => $jwt_token,
            'message' => 'User logged in successfully'
        ]);
    }

    public function logout(Request $request){
        $this->validate($request, [
            'token' => 'required'
        ]);

        try{
            JWTAuth::invalidate($request->token);
            
            return response()->json([
                'success' => true,
                'message' => 'User logged out successfully'
            ]);
        } catch (JWTException $exception){
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be logged out'
            ],500);
        }
    }

    public function getAuthUser(Request $request){
        $this->validate($request,[
            'token' => 'required'
        ]);

        $user = JWTAuth::authenticate($request->token);
        return response()->json(['user'=>$user]);
    }
    
    public function updateUser(Request $request){
        $this->validate($request,[
            'token' => 'required'
        ]);

        $user = JWTAuth::authenticate($request->token);
        
        if($request->password != ''){
            $user->password = bcrypt($request->password);
        }
        
        $updated = $user->fill($request->only(['name','email']))->save();

        if($updated){
            return response()->json([
                'success'=>true,
                'message' => 'User updated successfully'
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Sorry, user could not be updated'
            ],500);
        }
    }
}
