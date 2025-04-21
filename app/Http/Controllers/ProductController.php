<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\ProductRequest;
use App\Services\Image;
use CLog;

class ProductController
{
    
    public function index(){
        return inertia('Product/Index');
    }

    public function get(Request $req){
        return response(
            Product::where(function ($q) use($req){
                $search = $req['search'];
                if ($search){
                    $q->where('category', 'LIKE', $search.'%')
                        ->orWhere('name', 'LIKE', '%'. $search.'%');
                }
            })
            ->where('category', '!=' , 'Rabbit')
            ->paginate($req['page_size'] ?? 10)
        );
    }

    public function getUserScroll(Request $req){
        return response(
            Product::where('stock', '>', 0)
                ->where(function ($q) use($req){
                    if ($req['category']){
                        $q->where('category',  $req['category']);
                    }
                })
                ->where('active', 1)
                ->limit($req['limit'])
                ->get()
        );
    }

    public function rabbits(){
        return response(
            Product::select(['id', 'name'])
                ->where('category', 'Rabbit')
                ->get()
        );
    }

    public function store(ProductRequest $req){
        $data = $req->validated();
        $images = $req->file('images');
        if ($images){
            $names = '';
            foreach($images as $image){
                $names .= '|' . Image::proccessDefName($image, 'images');
            }
            $data['images'] = substr($names, 1);
        }
        Product::create($data);

        CLog::create('Created', 'Product');
    }

    public function update(ProductRequest $req, $id){
        $data = $req->validated();
        $images = $req->file('images');
        if ($images){
            $p = Product::find($id);
            foreach(explode('|', $p->images ?? '') as $name){
                Image::remove('images', $name);
            }

            $names = '';
            foreach($images as $image){
                $names .= '|' . Image::proccessDefName($image, 'images');
            }
            $data['images'] = substr($names, 1);
        } else
            unset($data['images']);
            
        Product::where('id', $id)->update($data);

        CLog::create('Updated', 'Product');
    }

    public function delete($id){
        $data = Product::find($id);
        foreach(explode('|', $data->images ?? '') as $name){
            Image::remove('images', $name);
        }
        $data->delete();

        CLog::create('Deleted', 'Product');
    }

}
