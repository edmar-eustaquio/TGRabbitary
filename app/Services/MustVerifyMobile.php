<?php
namespace App\Services;

use App\Events\SendSMSVerification;

use Twilio\Rest\Client;

trait MustVerifyMobile {

    public function hasVerifiedMobile(){
        return  ! is_null($this->mobile_verified_at);
    }

    public function markMobileAsVerified(){
        return $this->forceFill([
            'mobile_verified_at' => now()
        ])->save();
    }

    public function sendMobileVerification(){
        $code = random_int(1111, 9999);
        cache(['verification-mobile-code-'.auth()->user()->id => $code], 60 * 5);

        $client = new Client(env("TWILIO_SID"), env("TWILIO_AUTH_TOKEN"));

        $client->messages->create($this->phone_no, [
            'from' => env("TWILIO_PHONE_NUMBER"),
            'body' => 'TGRabbitary code: '. $code . '. Use in 5 mins. ',
        ]);

        // $this->notify(new SendSMSVerification());
    }
}
?>