<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Registro
    public function register(Request $request) {
        $request->validate([
            'name'=>'required|string',
            'email'=>'required|email|unique:users',
            'password'=>'required|string|min:6'
        ]);

        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password)
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json(['user'=>$user, 'token'=>$token], 201);
    }

    // Login
    public function login(Request $request) {
        $request->validate([
            'email'=>'required|email',
            'password'=>'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if(!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message'=>'Credenciais inválidas'], 401);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json(['user'=>$user, 'token'=>$token]);
    }

    // Logout
    public function logout(Request $request) {
        $request->user()->tokens()->delete();
        return response()->json(['message'=>'Logout realizado com sucesso']);
    }
}
