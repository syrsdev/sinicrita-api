<?php

namespace App\Http\Controllers\v1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {

            $credentials = $request->validate([
                'username' => ['required', 'unique:users,username'],
                'password' => ['required'],
                'role' => ['required'],
            ]);

            User::create($credentials);

            return response()->json(['status' => 'success', 'statusCode' => '200', 'message' => 'User registration successful'], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'statusCode' => '500', 'message' => $th->getMessage()], 500);
        }
    }
    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'username' => ['required'],
                'password' => ['required'],
            ]);

            $user = User::where('username', $credentials['username'])->first();

            if (!$user) {
                return response()->json(['status' => 'error', 'statusCode' => '404', 'message' => 'User not found'], 404);
            }

            if (!Auth::attempt($credentials)) {
                return response()->json(['status' => 'error', 'statusCode' => '401', 'message' => 'Unauthorized'], 401);
            }

            $request->session()->regenerate();
            return response()->json(['status' => 'success', 'statusCode' => '200', 'message' => 'Login successful'], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'statusCode' => '500', 'message' => $th->getMessage()], 500);
        }
    }

    public function logout(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'error', 'statusCode' => '401', 'message' => 'Not logged in.'], 401);
        }
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['status' => 'success', 'statusCode' => '200', 'message' => 'Logout successful'], 200);
    }
}