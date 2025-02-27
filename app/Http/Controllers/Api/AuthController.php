<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\helpers\ApiRseponse;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{

    public function register(RegisterRequest $request)
    {
        $data = $request->all();

        // Store image if provided
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $data['image'] = $path; 
        }

        // Generate a fake OTP
        $otp = 1111;

        // Set OTP and is_verified to false
        $data['otp'] = $otp;
        $data['is_verified'] = false;

        // Create user
        $user = User::create($data);


        return  response()->json([
            'message' => 'User created successfully. Please verify OTP.',
            'data' => [
                'email' => $user->email,
                'image' => url('storage/' . $user->image),
                'username' => $user->email,
                'otp' => $otp, // Remove this in production!
            ],
            'status' => 'success'
        ], 201);
        
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return  response()->json([
                'message' => 'Login failed. Invalid credentials.',
                'data' => null,
                'status' => 'fail'
            ], 401);
        }

        if (!$user->is_verified) {
            return  response()->json([
                'message' => 'Please verify your OTP before logging in.',
                'data' => null,
                'status' => 'fail'
            ], 403);
        }

        $data['token'] = $user->createToken('token')->plainTextToken;
        $data['username'] = $user->username;
        $data['email'] = $user->email;

        return  response()->json([
            'message' => 'Login successful',
            'data' => $data,
            'status' => 'success'
        ], 200);
    }


    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required',
        ]);

        $user = User::where('email', $request->email)->where('otp', $request->otp)->first();

        if (!$user) {
            return  response()->json([
                'message' => 'Invalid OTP',
                'data' => null,
                'status' => 'fail'
            ], 400);
        }

        $user->update(['is_verified' => true, 'otp' => null]); // OTP should not be reused

        return  response()->json([
            'message' => 'OTP verified successfully. You can now login.',
            'data' => null,
            'status' => 'success'
        ], 200);
    }

    public function logout(Request $request)
    {

        $request->user()->currentAccessToken()->delete();

        return  response()->json([
            'message' => 'logout successfully',
            'data' => null,
            'status' => 'success'
        ], 200);
        
    }

}
