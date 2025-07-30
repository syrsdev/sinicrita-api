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
                'password' => ['required', 'confirmed', 'min:8'],
                'role' => ['required'],
            ], [
                'username.required' => 'Username harus diisi',
                'username.unique' => 'Username sudah digunakan',
                'password.required' => 'Password harus diisi',
                'password.confirmed' => 'Password tidak cocok',
                'password.min' => 'Password minimal 8 karakter',
            ]);
            User::create($credentials);

            return response()->json(['status' => 'success', 'statusCode' => '200', 'message' => 'Berhasil daftar'], 200);
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
            ], [
                'username.required' => 'Username harus diisi',
                'password.required' => 'Password harus diisi',
            ]);

            $user = User::where('username', $credentials['username'])->first();

            if (!$user) {
                return response()->json(['status' => 'error', 'statusCode' => '404', 'message' => 'user tidak ditemukan'], 404);
            }

            if (!Auth::attempt($credentials)) {
                return response()->json(['status' => 'error', 'statusCode' => '401', 'message' => 'Username atau Password salah'], 401);
            }

            $request->session()->regenerate();
            return response()->json(['status' => 'success', 'statusCode' => '200', 'message' => 'Login berhasil', 'role' => $user->role], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'statusCode' => '500', 'message' => $th->getMessage()], 500);
        }
    }

    public function logout(Request $request)
    {
        auth('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['status' => 'success', 'statusCode' => '200', 'message' => 'Logout berhasil'], 200);
    }
}