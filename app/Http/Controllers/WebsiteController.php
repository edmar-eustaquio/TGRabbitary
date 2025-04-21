<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Blog;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Rating;
use App\Models\OrderDetails;

class WebsiteController
{
    
    public function index(){
        if (auth()->user() && auth()->user()->admin == 1)
            return redirect(route('dashboard'));

        return inertia('Website/Index', [
            'products' => Product::select(['id', 'name', 'images', 'price'])
                ->where('active', 1)
                ->where('stock', '>', 0)
                ->limit(10)
                ->get()
        ]);
    }

    public function shop(){
        return inertia('Website/Shop');
    }

    public function shopPerCategory($category){
        return inertia('Website/Shop', [
            'category' => $category
        ]);
    }

    public function about(){
        return inertia('Website/About');
    }

    public function blog(){
        // 'recent_blogs' => Blog::selectRaw('id, title, image, created_at, (select count(*) from comments where blogs.id=blog_id) AS comments, (select case when admin=1 then "Admin" else "User" end from users where users.id=blogs.owner_id limit 1) as role')
        return inertia('Website/Blog', [
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

    public function sblog($id){
        $blog = Blog::with(['comments.replies.replies.replies'])->where('id', $id)->orderByDesc('created_at')->first();
        
        abort_if(!$blog, 404);

        return inertia('Website/SBlog', [
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

   

    public function cart(){
        return inertia('Website/Cart', [
            'carts' => Cart::with(['product:id,name,images,stock,price'])
                ->where('user_id', auth()->user()->id)
                ->get()
        ]);
    }

    public function message(){
        return inertia('Website/Message');
    }

    public function profile(){
        return inertia('Website/Profile');
    }

    public function orderDetails($id){
        $order = Order::with([
                'details.product:id,name,price', 
                'details.rate:id,order_details_id'
            ])
            ->where('id', $id)
            ->where('user_id', auth()->user()->id)
            ->first();

        return inertia('Website/OrderDetails',[
            'order' => $order
        ]);
    }

    public function detailsOrder($id){
        $order = Order::with([
                'details.product:id,name,price', 
            ])
            ->where('id', $id)
            ->where('user_id', auth()->user()->id)
            ->first();

        return response($order);
    }

    public function show($id){
        $product = Product::where('active', 1)
            ->where('id', $id)
            ->first();
        abort_if(!$product, 404);
        
        $product->sold = OrderDetails::query()
            ->where('product_id', $id)
            ->whereHas('order', function($q){
                $q->where('status', 'Delivered');
            })
            ->sum('qty');

        
        $q =  Rating::whereHas('order_details', function($q) use($id){
            $q->where('product_id', $id);
        });
        $product->rating = $q->avg('rate');
        $product->rating_count = $q->count('id');
        
        return inertia('Website/ProductShow', [
            'product' => $product
        ]);
    }

    public function rating($product_id){
        $product = Product::select(['id', 'name', 'created_at'])->find($product_id);
        abort_if(!$product, 404);

        return inertia('Website/Rating', [
            'ratings' => Rating::with(['user:id,name', 'order_details.product:id,name,desc'])
                ->whereHas('order_details', function($q) use($product_id){
                    $q->where('product_id', $product_id);
                })
                ->get(),
            'product' => $product
        ]);
    }

}
