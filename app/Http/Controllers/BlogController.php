<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\Product;
use App\Http\Requests\BlogRequest;
use CLog;
use App\Services\Image;

class BlogController
{
    public function index(){
        return inertia('Blog/Index', [
            'products' => Product::select(['id', 'name', 'stock'])
                ->where('active', 1)
                ->where('stock', '>', 0)
                ->get(),
            'recent_blogs' => Blog::selectRaw('id, title, image, created_at, (select count(*) from comments where blogs.id=blog_id) AS comments, (select name from users where users.id=blogs.owner_id limit 1) as owner_name')
                ->orderByDesc('created_at')
                ->limit(5)
                ->get()
        ]);
    }
    public function show($id){
        $blog = Blog::with(['comments.replies.replies.replies'])->where('id', $id)->orderByDesc('created_at')->first();
        
        abort_if(!$blog, 404);

        return inertia('Blog/Show', [
            'blog' => $blog,
            'products' => Product::select(['id', 'name', 'stock'])
                ->where('active', 1)
                ->where('stock', '>', 0)
                ->get(),
            'recent_blogs' => Blog::selectRaw('id, title, image, created_at, (select count(*) from comments where blogs.id=blog_id) AS comments, (select name from users where users.id=blogs.owner_id limit 1) as owner_name')
                ->orderByDesc('created_at')
                ->limit(5)
                ->get()
        ]);
    }

    public function get(Request $req){
        return response(
            Blog::all()
        );
    }
    
    public function getScroll(Request $req){
        return response(
            Blog::selectRaw("id, title, image, CASE WHEN LENGTH(body) < 126 THEN body ELSE CONCAT(LEFT(body, 125), '...') END AS 'body', created_at, (select count(*) from comments where blogs.id=blog_id) AS comments")
                ->orderByDesc('created_at')
                ->limit($req['limit'])
                ->get()
        );
    }

    public function store(BlogRequest $req){
        $data = $req->validated();
        $data['owner_id'] = auth()->user()->id;
        $data['image']= Image::proccessDefName($req->file('image'), 'images/blog');
        Blog::create($data);

        CLog::create('Created', 'Blog');
    }

    public function update(BlogRequest $req, $id){
        $v = Blog::select(['id', 'image'])->where('id', $id)->first();

        abort_if(!$v, 404);

        $data = $req->validated();
        $image = $req->file('image');
        if ($image){
            Image::remove('image', $v->image);
            $data['image'] = Image::proccessDefName($image, 'images/blog');
        } 
        else
            unset($req['image']);

        $v->update($data);

        CLog::create('Updated', 'Blog');
    }

    public function delete($id){
        Blog::where('id', $id)->delete();

        CLog::create('Deleted', 'Blog');
    }
}
