<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'body',
        'image',
        'owner_id'
    ];

    public function comments(){
        return $this->hasMany(Comment::class, 'blog_id');
    }

    public function owner(){
        return $this->belongsTo(User::class, 'owner_id');
    }
}
