<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\LoginRequest;
use Auth;
use App\Notifications\LoginNotification;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    public function login (LoginRequest $request){

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if(auth()->attempt($credentials )){
          $user = Auth::user();

          $user->tokens()->delete();
          
          $success['token'] = $user->createToken(request()->userAgent())->plainTextToken;
          $success['name'] = $user->name;
          $success['success'] = true;
          $user->notify(new LoginNotification());
          return response()->json( $success,200);
          
        }else{
          return response()->json(['error'=> __('auth.Unauthorised')],401);
        }

  }

public function logout(Request $request)
{
    $request->user()->tokens()->delete();

    return response()->json(['message' => 'Logged out successfully'], 200);
}

}
