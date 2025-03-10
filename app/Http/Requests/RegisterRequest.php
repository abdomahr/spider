<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
           'email' => 'required|email|unique:users,email',
           'username' => 'required|string|regex:/^\S*$/|min:3|max:20',
           'password' => 'sometimes|string|min:8|confirmed',         
           'image' => 'required|image|mimes:png,jpg|max:5120',           

        ];
    }
}
