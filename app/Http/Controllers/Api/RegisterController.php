<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use App\Http\Requests\Auth\RegistrationRequest;
use App\Notifications\EmailverificationNotification;

class RegisterController extends Controller
{
    public function register(RegistrationRequest $request){
        
        $newuser = $request->validated();

        $newuser['password'] = Hash::make($newuser['password']);

        $user = User::create($newuser);

        $success['token'] = $user->createToken('user',['app:all'])->plainTextToken;
        $success['name'] = $user->name;
        $success['success'] = true;
        $user->notify(new EmailverificationNotification());

        return response()->json($success, 200);

    }
}
