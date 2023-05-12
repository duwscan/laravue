<?php

namespace App\Http\Controllers;

use App\Laravue\JsonResponse;
use App\Laravue\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->isAdmin() || $user->notUser()) {
            $posts = Post::with('user')->paginate(10);
            return response()->json(new JsonResponse($posts));
        } else {
            $posts = $user->posts()->with('user')->paginate(10);
            return response()->json(new JsonResponse($posts));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = $request->user();
        $post = new Post();
        $post->title = $request->input('title');
        $post->content = $request->input('content');
        $post->user_id = $user->id;
        $post->save();

        return response()->json([
            'success' => true,
            'message' => 'Post created successfully',
            'data' => $post,
        ], Response::HTTP_CREATED);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['error' => 'Post not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json(['data' => $post], Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        if ($post->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if (!$request->has('title') && !$request->has('body')) {
            return response()->json(['message' => 'Cancel update'], 422);
        }

        $post->update([
            'title' => $request->title,
            'body' => $request->body,
        ]);

        return response()->json(['message' => 'Post updated successfully', 'post' => $post]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        if ($post->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $post->delete();

        return response()->json(['message' => 'Post deleted successfully']);
    }
}
