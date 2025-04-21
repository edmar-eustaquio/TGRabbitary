<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;

class VerifyMobileController
{
    
    public function index()
    {
        if (auth()->user()->hasVerifiedMobile()) 
            return redirect(route('home'));

        return inertia('Auth/VerifyMobile');
    }

    public function verify(Request $req){
        $req->validate([
            'code' => 'required|numeric'
        ]);

        if ($req->code == cache('verification-mobile-code-'.auth()->user()->id)){
            $req->user()->markMobileAsVerified();

            return response('done');
        }

        abort(404);
    }
    public function resend(){
        auth()->user()->sendMobileVerification();
    }
}
