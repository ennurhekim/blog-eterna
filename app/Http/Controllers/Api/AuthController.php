<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'surname' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed'
            ]);

            $user = User::create([
                'email' => $request->email,
                'name' => $request->name,
                'surname' => $request->surname,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
            ]);

            $user->assignRole(['reader']);
            
            $token = $user->createToken('auth_token')->plainTextToken;
            return response_json(true, __("validation.success_register"), ['token' => $token]);
        } catch (\Illuminate\Validation\ValidationException $t) {
            return response_json(false, __("validation.some_error"), $t->errors());
        }
    }
    public function login(Request $request)
    {
        try {
            $request->validate([
                'password' => 'required|string',
            ]);
            $user = User::where(function ($query) use ($request) {
                return $query->where('email', $request->email)
                    ->orwhere('phone', $request->phone);
            })->first();

            if (! $user || ! Hash::check($request->password, $user->password)) {
                return response_json(true, "Hata");
            }
            // Kullanıcı için yeni bir token oluştur
            $token = $user->createToken('auth_token')->plainTextToken;
            return response_json(true, __("validation.success_login"), ['token' => $token]);
        } catch (\Illuminate\Validation\ValidationException $t) {

            return response_json(false, __("validation.some_error"), $t->errors());
        }
    }
}
