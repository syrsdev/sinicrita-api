<?php

namespace App\Http\Controllers\v1\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\chat_session;
use App\Models\post;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $totalPencerita = User::where('role', 'pencerita')->count();
            $totalPost = post::count();
            $totalChat = chat_session::whereNotNull('post_id')->count();

            $users = User::where('role', '!=', 'admin')->limit(7)->get();

            return response()->json(['status' => 'success', 'statusCode' => '200', 'data' => ['totalPencerita' => $totalPencerita, 'totalPost' => $totalPost, 'totalChat' => $totalChat, 'users' => $users]], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'statusCode' => '500', 'message' => $th->getMessage()], 500);
        }
    }
}
