<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function index()
    {
        try {
            $data = User::all();

            if ($data == null) {
                return response()->json(['status' => 'error', 'statusCode' => '404', 'message' => 'User tidak ditemukan'], 404);
            }

            return response()->json(['status' => 'success', 'statusCode' => '200', 'data' => $data], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'statusCode' => '500', 'message' => $th->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $username)
    {
        try {
            $data = User::where('username', $username)->first();

            if ($data == null) {
                return response()->json(['status' => 'error', 'statusCode' => '404', 'message' => 'User tidak ditemukan'], 404);
            }

            $data->delete();
            return response()->json(['status' => 'success', 'statusCode' => '200', 'message' => 'User berhasil dihapus'], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'statusCode' => '500', 'message' => $th->getMessage()], 500);
        }
    }
}