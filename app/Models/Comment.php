<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    
    protected $with = ['owner:id,name'];

    protected $fillable = [
        'blog_id',
        'owner_id',
        'reply_to_id',
        'body',
    ];

    public function replies(){
        return $this->hasMany(Comment::class, 'reply_to_id')->orderBy('created_at');
    }
    public function owner(){
        return $this->belongsTo(User::class, 'owner_id');
    }
    public function blog(){
        return $this->belongsTo(Blog::class, 'blog_id');
    }
    
}
