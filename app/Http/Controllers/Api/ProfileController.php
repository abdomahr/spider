<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\UserResource;

class ProfileController extends Controller
{ 
    public function update(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'username' => 'sometimes|string|max:255|unique:users,username,' . $user->id,
            'email' => 'sometimes|email|max:255|unique:users,email,' . $user->id,
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'sometimes|string|min:8',
            'new_password' => 'required_with:password|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return  response()->json([
                'message' => 'validation error',
                'data' => null,
                'errors' => $validator->errors()->get('*'),
                'status' => 'fail'
            ], 422);
        }

        if ($request->has('username')) {
            $user->username = $request->username;
        }
        
        if ($request->has('email')) {
            $user->email = $request->email;
        }
        
        if ($request->hasFile('image')) {

            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }

        
            $path = $request->file('image')->store('images', 'public');

      
            $user->image = $path;
        }

        if(!Hash::check($request->password, $user->password)) {
            return  response()->json([
                'message' => 'Wrong password',
                'data' => null,
                'status' => 'fail'
            ], 422);
        }

        if ($request->has('new_password')) {
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return  response()->json([
            'message' => 'Profile updated successfully ',
            'data' => new UserResource($user),
            'status' => 'success'
        ], 200);
    }


    public function show() {
        $user = Auth::user();

        return  response()->json([
            'message' => null,
            'data' => new UserResource($user),
            'status' => 'success'
        ], 200);
    }
}