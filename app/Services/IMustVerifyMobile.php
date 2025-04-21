<?php
namespace App\Services;

interface IMustVerifyMobile {

    public function hasVerifiedMobile();

    public function markMobileAsVerified();

    public function sendMobileVerification();

}
?>