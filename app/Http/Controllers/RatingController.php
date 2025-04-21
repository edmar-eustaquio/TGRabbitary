<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rating;
use App\Models\Product;
use App\Models\User;
use App\Services\Image;

class RatingController
{

    public function add(Request $req){
        $req->validate([
            'order_details_id' => 'required|exists:order_details,id',
            'rate' => 'required',
            'comment' => 'required',
            'images' => 'nullable',
            // 'images.*' => 'file|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'images.*' => 'file|mimetypes:image/*,video/*',
        ]);

        $images = $req->file('images');
        $names = '';
        if ($images){
            foreach($images as $image){
                $names .= '|' . Image::proccessDefName($image, 'images/reviews');
            }
            $names = substr($names,  1);
        } 
        else
            $names = null;

        Rating::create([
            'order_details_id' => $req['order_details_id'],
            'rate' => $req['rate'],
            'comment' => $req['comment'],
            'user_id' => auth()->user()->id,
            'images' => $names,
        ]);
    }
}
