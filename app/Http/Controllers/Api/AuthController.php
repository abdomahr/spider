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
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('assets/images'), $imageName);
            $data['image'] = 'assets/images/' . $imageName; 
        }

        // Generate a fake OTP
        $otp = 1111;

        // Set OTP and is_verified to false
        $data['otp'] = $otp;
        $data['is_verified'] = false;

        // Create user
        $user = User::create($data);

        return ApiRseponse::sendresponse(201, 'User created successfully. Please verify OTP.', [
            'email' => $user->email,
            'image' => url($user->image),
            'username' => $user->email,
            'otp' => $otp, // Remove this in production!
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return ApiRseponse::sendresponse(401, 'Login failed. Invalid credentials.');
        }

        if (!$user->is_verified) {
            return ApiRseponse::sendresponse(403, 'Please verify your OTP before logging in.');
        }

        $data['token'] = $user->createToken('token')->plainTextToken;
        $data['username'] = $user->username;
        $data['email'] = $user->email;

        return ApiRseponse::sendresponse(200, 'Login successful', $data);
    }


    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required',
        ]);

        $user = User::where('email', $request->email)->where('otp', $request->otp)->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid OTP'], 400);
        }

        $user->update(['is_verified' => true, 'otp' => null]); // OTP should not be reused

        return response()->json(['message' => 'OTP verified successfully. You can now login.']);
    }

    public function logout(Request $request)
    {

        $request->user()->currentAccessToken()->delete();
        return ApiRseponse::sendresponse(200, 'logout successfully', []);
    }

}
