<?php

namespace App\Http\Controllers\v1\Post;

use App\Http\Controllers\Controller;
use App\Models\post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = post::with('user')->orderBy('created_at', 'asc')->get();

            if (count($data) == 0) {
                return response()->json(['status' => 'error', 'statusCode' => '404', 'message' => "There's no post yet"], 404);
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

            // $credentials['user_id'] = Auth::user()->id;

            post::create($credentials);

            return response()->json(['status' => 'success', 'statusCode' => '200', 'message' => 'Post created successfully'], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'statusCode' => '500', 'message' => $th->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = post::find($id);
        if ($data == null) {
            return response()->json(['status' => 'error', 'statusCode' => '404', 'message' => 'Post not found'], 404);
        }
        return response()->json(['status' => 'success', 'statusCode' => '200', 'message' => $data], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $data = post::find($id);
            if ($data == null) {
                return response()->json(['status' => 'error', 'statusCode' => '404', 'message' => 'Post not found'], 404);
            }

            $credentials = $request->validate([
                'content' => 'required',
            ]);

            $data->update($credentials);

            return response()->json(['status' => 'success', 'statusCode' => '200', 'message' => 'Post updated successfully'], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'statusCode' => '500', 'message' => $th->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = post::find($id);
        if ($data == null) {
            return response()->json(['status' => 'error', 'statusCode' => '404', 'message' => 'Post not found'], 404);
        }
        $data->delete();
        return response()->json(['status' => 'success', 'statusCode' => '200', 'message' => 'Post deleted successfully'], 200);
    }
}
