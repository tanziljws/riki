<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Comment;
use App\Models\Gallery;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InteractionController extends Controller
{
    public function state(Gallery $gallery, Request $request)
    {
        $user = $request->user();
        $likesCount = Like::where('gallery_id', $gallery->id)->count();
        $likedByMe = $user ? Like::where('gallery_id', $gallery->id)->where('user_id', $user->id)->exists() : false;
        $comments = Comment::with('user:id,name,avatar,email')
            ->where('gallery_id', $gallery->id)
            ->latest()
            ->limit(50)
            ->get()
            ->map(function ($c) {
                return [
                    'id' => $c->id,
                    'user_name' => $c->user->name,
                    'user_avatar' => $c->user->avatar,
                    'user_email' => $c->user->email,
                    'text' => e($c->text),
                    'created_at' => $c->created_at->toIso8601String(),
                    'mine' => auth()->id() === $c->user_id,
                ];
            });
        return response()->json([
            'likes_count' => $likesCount,
            'liked_by_me' => $likedByMe,
            'comments' => $comments,
        ])->header('Access-Control-Allow-Origin', '*')
          ->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
          ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
    }

    public function toggleLike(Gallery $gallery, Request $request)
    {
        $request->validate([]); // no payload
        $user = $request->user();
        abort_unless($user, 401);

        $like = Like::where('gallery_id', $gallery->id)->where('user_id', $user->id)->first();
        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            $like = Like::create(['gallery_id' => $gallery->id, 'user_id' => $user->id]);
            $liked = true;
            Activity::create([
                'actor_user_id' => $user->id,
                'type' => 'like',
                'gallery_id' => $gallery->id,
                'comment_id' => null,
                'meta' => null,
            ]);
        }
        $count = Like::where('gallery_id', $gallery->id)->count();
        return response()->json(['liked' => $liked, 'likes_count' => $count]);
    }

    public function addComment(Gallery $gallery, Request $request)
    {
        $data = $request->validate([
            'text' => 'required|string|max:1000',
        ]);
        $user = $request->user();
        abort_unless($user, 401);

        return DB::transaction(function () use ($gallery, $user, $data) {
            $comment = Comment::create([
                'gallery_id' => $gallery->id,
                'user_id' => $user->id,
                'text' => $data['text'],
            ]);

            Activity::create([
                'actor_user_id' => $user->id,
                'type' => 'comment',
                'gallery_id' => $gallery->id,
                'comment_id' => $comment->id,
                'meta' => null,
            ]);

            return response()->json([
                'id' => $comment->id,
                'user_name' => $user->name,
                'user_avatar' => $user->avatar,
                'user_email' => $user->email,
                'text' => e($comment->text),
                'created_at' => $comment->created_at->toIso8601String(),
            ], 201);
        });
    }

    public function deleteComment(Comment $comment, Request $request)
    {
        $user = $request->user();
        abort_unless($user, 401);
        abort_if($comment->user_id !== $user->id, 403);
        // Hapus aktivitas yang berkaitan dengan komentar ini
        Activity::where('comment_id', $comment->id)->delete();
        // Soft delete komentar
        $comment->delete();
        return response()->json(['deleted' => true]);
    }
}
