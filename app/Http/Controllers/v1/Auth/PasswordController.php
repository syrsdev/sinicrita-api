<?php

namespace App\Http\Controllers\v1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PasswordController extends Controller
{
    public function ChangePassword(Request $request)
    {
        $credentials = $request->validate([
            'old_password' => ['required', 'current_password'],
            'new_password' => ['required', 'confirmed', 'min:8'],
        ], [
            'old_password.required' => 'Password lama harus diisi',
            'old_password.current_password' => 'Password lama salah',
            'new_password.required' => 'Password baru harus diisi',
            'new_password.confirmed' => 'Password baru tidak cocok',
            'new_password.min' => 'Password baru minimal 8 karakter',
        ]);
        
        User::where('id', Auth::user()->id)->update([
            'password' => bcrypt($credentials['new_password']),
        ]);
        return response()->json(['message' => 'Password changed'], 200);
    }
}