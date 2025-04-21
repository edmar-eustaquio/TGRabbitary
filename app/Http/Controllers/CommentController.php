<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController
{
    public function store(Request $req){
        $req->validate([
            'body' => 'required',
            'blog_id' => 'required|exists:blogs,id'
        ]);
        
        Comment::create([
            'body' => $req['body'],
            'blog_id' => $req['blog_id'],
            'owner_id' => auth()->user()->id,
        ]);
    }
    
    public function reply(Request $req){
        $req->validate([
            'body' => 'required',
            'reply_to_id' => 'required|exists:comments,id',
        ]);
        
        Comment::create([
            'body' => $req['body'],
            'reply_to_id' => $req['reply_to_id'],
            'owner_id' => auth()->user()->id,
        ]);
    }
}
