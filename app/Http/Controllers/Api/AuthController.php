<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\helpers\ApiRseponse;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;


class AuthController extends Controller
{ 
    
    public function register(RegisterRequest $request)
    {
        $data = $request->all();
        $data['image'] = $request->image->store('images', 'public');
        $user = User::create($data);
        $user->forceFill([
        'token'=>$user->createToken('token')->plainTextToken
       ]);
        return ApiRseponse::sendresponse(201, 'User created successfully',new UserResource($user));

       
    }
    
    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string',
        ]);

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $data['token'] = $user->createToken('token')->plainTextToken;
            $data['username'] = $user->username;
            $data['email'] = $user->email;
            return ApiRseponse::sendresponse(200, 'login successfully', $data);
        } else {
            return ApiRseponse::sendresponse(401, 'login failed');
        }


    }
    public function logout(Request $request)
    {

        $request->user()->currentAccessToken()->delete();
        return ApiRseponse::sendresponse(200, 'logout successfully',[]);
    }
}
