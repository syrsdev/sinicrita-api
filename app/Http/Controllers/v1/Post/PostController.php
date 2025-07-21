<?php

namespace App\Http\Controllers\v1\Post;

use App\Http\Controllers\Controller;
use App\Models\post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($user)
    {
        try {
            $getUser = User::where('username', $user)->first();
            if ($getUser->role == 'pencerita') {
                $data = post::with('user')->where('user_id', $getUser->id)->orderBy('created_at', 'desc')->get();
            } else {
                $data = post::with('user')->orderBy('created_at', 'desc')->get();
            }

            return response()->json(['status' => 'success', 'statusCode' => '200', 'data' => $data], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'statusCode' => '500', 'message' => $th->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $credentials = $request->validate([
                'content' => 'required',
                'user_id' => 'required'
            ]);

            post::create($credentials);

            return response()->json(['status' => 'success', 'statusCode' => '200', 'message' => 'Cerita berhasil dibuat'], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'statusCode' => '500', 'message' => $th->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $data = post::with('user')->where('slug', $slug)->first();
        if ($data == null) {
            return response()->json(['status' => 'error', 'statusCode' => '404', 'message' => 'Cerita tidak ditemukan'], 404);
        }
        return response()->json(['status' => 'success', 'statusCode' => '200', 'data' => $data], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $slug)
    {
        try {
            $data = post::where('slug', $slug)->first();

            if ($data == null) {
                return response()->json(['status' => 'error', 'statusCode' => '404', 'message' => 'Cerita tidak ditemukan'], 404);
            }

            $credentials = $request->validate([
                'content' => 'required',
            ]);

            $credentials['slug'] = Str::slug($credentials['content']);


            $data->update($credentials);

            return response()->json(['status' => 'success', 'statusCode' => '200', 'message' => 'Cerita berhasil diupdate'], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'statusCode' => '500', 'message' => $th->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $slug)
    {
        try {
            $data = post::where('slug', $slug)->first();

            if ($data == null) {
                return response()->json(['status' => 'error', 'statusCode' => '404', 'message' => 'Cerita tidak ditemukan'], 404);
            }
            $data->delete();
            return response()->json(['status' => 'success', 'statusCode' => '200', 'message' => 'Cerita berhasil dihapus'], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'statusCode' => '500', 'message' => $th->getMessage()], 500);
        }
    }
    public function updateStatus(Request $request, string $slug)
    {
        try {
            $credentials = $request->validate([
                'status' => 'required',
            ]);

            $data = post::where('slug', $slug)->first();

            if ($data == null) {
                return response()->json(['status' => 'error', 'statusCode' => '404', 'message' => 'Cerita tidak ditemukan'], 404);
            }

            $data->status = $credentials['status'];
            $data->save();
            return response()->json(['status' => 'success', 'statusCode' => '200', 'message' => 'Cerita berhasil dihapus'], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'statusCode' => '500', 'message' => $th->getMessage()], 500);
        }
    }
}