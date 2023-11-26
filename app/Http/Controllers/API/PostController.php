<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Log;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::select("id", "user_id", "title", "content", "created_at")->with("user")->get();
        return ["status" => 200, "posts" => $posts];


    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $payload = $request->validate([
            "title" => "required|min:10|max:191",
            "content" => "required|min:10|max:20000"
        ]);

        try {
            $user = $request->user();
            $payload["user_id"] = $user->id;
            $post = Post::create($payload);
            return ["status" => 200, "message" => "Post created successfully", "post" => $post];
        } catch (\Exception $err) {
            Log::info("post create err =>" . $err->getMessage());
            return ["status" => 500, "message" => "Something went wrong.pls try again!"];
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

    }
}
