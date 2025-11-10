<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id','gallery_id','text'];

    public function user(){ return $this->belongsTo(User::class); }
    public function gallery(){ return $this->belongsTo(Gallery::class); }
}
