<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use App\Events\MessageEvent;
use App\Services\Image;

class MessageController
{

    public function list(){
        return inertia('Message/List', [
            'data' => User::select(['id', 'name'])
                ->withCount(['user_messages' => function($q){
                    $q->whereNull('seen_at')
                        ->whereNull('admin_id');
                }])
                ->where('admin', 0)
                ->get()
        ]);
    }

    public function index($user_id){
        $user = User::where('id', $user_id)->where('admin', 0)->first();
        abort_if(!$user, 404);

        return inertia('Message/Index', ['_user' => $user]);
    }

    public function get(){
        Message::whereNotNull('admin_id')
            ->whereNull('seen_at')
            ->where('user_id', auth()->user()->id)
            ->update([
                'seen_at' => date('Y-m-d  H:i:s'),
            ]);

        return response([
            'messages' => Message::where('user_id', auth()->user()->id)
                ->orderBy('created_at')
                ->get(),
            'last_online_at' => User::select(['last_online_at'])
                ->where('admin', 1)
                ->orderByDesc('last_online_at')
                ->first()
                ?->last_online_at
        ]);
    }

    public function sendMessage(Request $req){
        Message::create([
            'user_id' =>  auth()->user()->id,
            'body' =>  $req->input('body'),
        ]);
        
        event(new  MessageEvent());
    }

    public function getAdmin($user_id){
        Message::whereNull('admin_id')
            ->whereNull('seen_at')
            ->where('user_id', $user_id)
            ->update([
                'seen_at' => date('Y-m-d  H:i:s'),
            ]);

        return response([
            'messages' => Message::where('user_id', $user_id)
                ->orderBy('created_at')
                ->get(),
            'last_online_at' => User::select(['last_online_at'])
                ->where('id', $user_id)
                ->first()
                ?->last_online_at
        ]);
    }

    public function sendMessageAdmin(Request $req){
        Message::create([
            'admin_id' =>  auth()->user()->id,
            'user_id' =>  $req['user_id'],
            'body' =>  $req->input('body'),
        ]);
        
        event(new  MessageEvent());
    }

    public function sendImage(Request $req){
        $m = Message::create([
            'user_id' =>  auth()->user()->id,
            'images' =>  $this->uploadImages($req->file('images')),
        ]);

        event(new  MessageEvent());

        if ($req['send_reference']){
            $number = \App\Models\Setting::select(['number'])->first()?->number;
            if ($number){
                (new \Twilio\Rest\Client(env("TWILIO_SID"), env("TWILIO_AUTH_TOKEN")))
                    ->messages
                    ->create($number, [
                        'from' => env("TWILIO_PHONE_NUMBER"),
                        'body' => 'TGRabbitary: '. auth()->user()->name . ' sent you a reference number. ',
                    ]);
            }
        }

        return response($m?->id);
    }

    public function sendImageAdmin(Request $req){
        Message::create([
            'admin_id' =>  auth()->user()->id,
            'user_id' =>  $req['user_id'],
            'images' =>  $this->uploadImages($req->file('images')),
        ]);
        
        event(new  MessageEvent());
    }

    private function uploadImages($images){
        if(!$images) return null;

        $names = '';
        foreach($images as $image){
            $names .= '|' . Image::proccessDefName($image, 'images/messages');
        }
        return substr($names,  1);
    }

}
