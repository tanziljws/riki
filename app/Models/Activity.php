<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = ['actor_user_id','type','gallery_id','comment_id','meta'];

    protected $casts = [
        'meta' => 'array',
    ];

    public function actor(){ return $this->belongsTo(User::class, 'actor_user_id'); }
    public function gallery(){ return $this->belongsTo(Gallery::class); }
    public function comment(){ return $this->belongsTo(Comment::class); }
}
