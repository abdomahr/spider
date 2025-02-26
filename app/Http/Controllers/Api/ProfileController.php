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
            'password' => 'sometimes|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->has('username')) {
            $user->username = $request->username;
        }
        
        if ($request->has('email')) {
            $user->email = $request->email;
        }
        
        if ($request->hasFile('image')) {
            // حذف الصورة القديمة إذا كانت موجودة
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }

            // حفظ الصورة الجديدة في storage/app/public/images
            $path = $request->file('image')->store('images', 'public');

            // حفظ مسار الصورة في قاعدة البيانات
            $user->image = $path;
        }

        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => new UserResource($user),
        ], 200);
    }
}