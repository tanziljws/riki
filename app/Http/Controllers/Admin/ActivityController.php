<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\Comment;
use App\Models\Gallery;
use App\Models\User;

class ActivityController extends Controller
{
    public function index()
    {
        $q = request('q');
        $type = request('type'); // 'like' | 'comment' | null

        $base = Activity::with(['actor:id,name','gallery:id,title,image','comment:id,text'])
            ->when($q, function($query) use ($q){
                $query->whereHas('actor', function($s) use ($q){ $s->where('name','like',"%$q%"); })
                      ->orWhereHas('gallery', function($s) use ($q){ $s->where('title','like',"%$q%"); });
            })
            ->orderByDesc('created_at');

        // counts for tabs
        $countLike = (clone $base)->where('type','like')->count();
        $countComment = (clone $base)->where('type','comment')->count();

        if ($type === 'like' || $type === 'comment') {
            $items = (clone $base)->where('type', $type)->paginate(15)->withQueryString();
            return view('admin.aktivitas.index', [
                'items' => $items,
                'q' => $q,
                'type' => $type,
                'countLike' => $countLike,
                'countComment' => $countComment,
            ]);
        }

        // No filter: fetch two lists
        $comments = (clone $base)->where('type','comment')->paginate(10, ['*'], 'pc')->withQueryString();
        $likes = (clone $base)->where('type','like')->paginate(10, ['*'], 'pl')->withQueryString();

        return view('admin.aktivitas.index', [
            'q' => $q,
            'type' => null,
            'comments' => $comments,
            'likes' => $likes,
            'countLike' => $countLike,
            'countComment' => $countComment,
        ]);
    }

    public function destroyComment(Comment $comment)
    {
        // hapus semua activity yang refer ke komentar ini
        Activity::where('comment_id', $comment->id)->delete();
        // soft delete komentar
        $comment->delete();
        return back()->with('status', 'Komentar berhasil dihapus.');
    }
}
