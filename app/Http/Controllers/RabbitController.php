<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rabbit;
use App\Models\Product;
use App\Http\Requests\RabbitRequest;
use App\Services\Image;
use CLog;

class RabbitController 
{
    public function index() {
        return inertia('Rabbit/Index');
    }
    public function breed() {
        return inertia('Rabbit/Breed');
    }
    public function weight(){
        return inertia('Rabbit/Weight');
    }
    

    public function dropdown(){
        return response(
            Rabbit::select(['id', 'name'])->get()
        );
    }
    public function get(Request $req){
        return response(
            Rabbit::with(['product:id,name', 'latest_weight', 'illness:illness,id,rabbit_id'])->paginate($req['page_size'] ?? 10)
        );
    }

    public function breeds(Request $req){
        return response(
            Product::where('category', 'Rabbit')->paginate($req['page_size'] ?? 10)
        );
    }

    public function getBucks(){
        return response(
            Rabbit::select(['id', 'name'])->where('gender', 'Buck')->get()
        );
    }

    public function getDoes(){
        return response(
            Rabbit::select(['id', 'name'])->where('gender', 'Doe')->get()
        );
    }

    public function store(RabbitRequest $req){
        $data = $req->validated();
        $data['images']= Image::proccessDefName($req->file('images'), 'images');
        $data['birth'] = date('Y-m-d', strtotime(substr($data['birth'], 0, 10) . ' +1 day'));

        $rabbit = Rabbit::create($data);

        if ($req['illness'] && $req['date']){
            \App\Models\Illness::create([
                'rabbit_id' => $rabbit->id,
                'illness' => $req['illness'],
                'date' => $req['date'],
            ]);
        }

        if ($req['status'] == 'For Sale') {
            $i = Product::select(['id', 'stock'])->where('id', $req['product_id'])->first();
            $i->update([
                'stock' => ((int) $i->stock) + 1
            ]);
        }

        CLog::create('Created', 'Rabbit');
    }

    public function update(RabbitRequest $req, $id){
        $v = Rabbit::select(['id', 'status', 'sold', 'images', 'birth'])->where('id', $id)->first();
        abort_if(!$v, 404);

        $data = $req->validated();
        $images = $req->file('images');
        if ($images){
            Image::remove('images', $v->images);
            $data['images'] = Image::proccessDefName($images, 'images');
        } 
        else
            unset($req['images']);

        if($v->birth != $data['birth'])
            $data['birth'] = date('Y-m-d', strtotime(substr($data['birth'], 0, 10) . ' +1 day'));

        if ($v->sold == 0 && $req['status'] == 'For Sale' && $v->status != 'For Sale') {
            $i = Product::select(['id', 'stock'])->where('id', $req['product_id'])->first();
            $i->update([
                'stock' => ((int) $i->stock) + 1
            ]);
        }
        else if ($v->sold == 0 && $req['status'] != 'For Sale' && $v->status == 'For Sale') {
            $i = Product::select(['id', 'stock'])->where('id', $req['product_id'])->first();
            $i->update([
                'stock' => ((int) $i->stock) - 1
            ]);
        }
        
        $v->update($data);

        if ($req['illness'] && $req['date']){
            \App\Models\Illness::create([
                'rabbit_id' => $v->id,
                'illness' => $req['illness'],
                'date' => $req['date'],
            ]);
        }

        CLog::create('Updated', 'Rabbit');
    }

    public function delete($id){
        $v = Rabbit::select(['id', 'status', 'sold', 'product_id'])->where('id', $id)->first();
        abort_if(!$v, 404);

        $data = Rabbit::find($id);
        foreach(explode('|', $data->images ?? '') as $name){
            Image::remove('images', $name);
        }
        $data->delete();

        

        if ($v->sold == 0 && $v->status == 'For Sale') {
            $i = Product::select(['id', 'stock'])->where('id', $v->product_id)->first();
            $i->update([
                'stock' => ((int) $i->stock) - 1
            ]);
        }

        $v->delete();

        CLog::create('Deleted', 'Rabbit');
    }
}
