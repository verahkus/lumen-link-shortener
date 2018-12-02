<?php

namespace App\Rules;

use Illuminate\Support\Facades\Validator;

class UrlBase64KeyRule
{
    public static function validate(){
        $regex = '%^(?:(?:https?|ftp)://)(?:\S+(?::\S*)?@|\d{1,3}(?:\.\d{1,3}){3}|(?:(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)(?:\.(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)*(?:\.[a-z\x{00a1}-\x{ffff}]{2,6}))(?::\d+)?(?:[^\s]*)?$%iu';

        Validator::extend('base64key', function ($attribute, $value) {
            return base64_decode(str_replace(config('app.site_url'),"",trim($value)),true);
        });
    }
}