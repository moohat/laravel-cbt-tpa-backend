<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResource;
use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function register()
    {
        $validatedData = request()->validate([
            'name' => 'required|max:55',
            'email' => 'email|required|unique:users',
            'password' => 'required',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role' => 'user',
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'acces_token' => $token,
            'user' => UserResource::make($user),
        ]);
    }

    // login
    public function login()
    {
        $loginData = request()->validate([
            'email' => 'email|required',
            'password' => 'required',
        ]);

        $user = User::where('email', $loginData['email'])->first();

        if (!$user || !Hash::check($loginData['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'user' => UserResource::make($user),
        ]);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();

        // Kembalikan respons JSON
        return response()->json(
            [
                'message' => 'Berhasil logout',
                'user_name' => $user->name,
                'logout_at' => Carbon::now(),
            ],
            200,
        );
    }
}
