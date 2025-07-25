<?php

namespace App\Lib;

use App\Constants\Status;
use App\Models\Extension;

class Captcha{

    /*
    |--------------------------------------------------------------------------
    | Captcha
    |--------------------------------------------------------------------------
    |
    | This class is using verify and show captcha. Here is currently available
    | custom captcha and google recaptcha2. Developer can use verify method
    | to verify all captcha or can use separately if required
    |
    */

    /**
    * Google recaptcha2 script
    *
    * @return string
    */
    public static function reCaptcha(){
        $reCaptcha = Extension::where('act', 'google-recaptcha2')->where('status', Status::ENABLE)->first();
        return $reCaptcha ? $reCaptcha->generateScript() : null;
    }

    /**
    * Custom captcha script
    *
    * @return string
    */
    public static function customCaptcha($width = '100%', $height = 46, $bgColor = '#003'){

        $textColor = '#'.gs('base_color');
        $captcha = Extension::where('act', 'custom-captcha')->where('status', Status::ENABLE)->first();
        if (!$captcha) {
            return 0;
        }
        $code = rand(100000, 999999);
        $char = str_split($code);
        $ret = '<link href="https://fonts.googleapis.com/css?family=Henny+Penny&display=swap" rel="stylesheet">';
        $ret .= '<div style="height: ' . $height . 'px; line-height: ' . $height . 'px; width:' . $width . '; text-align: center; background-color: ' . $bgColor . '; color: ' . $textColor . '; font-size: ' . ($height - 20) . 'px; font-weight: bold; letter-spacing: 20px; font-family: \'Henny Penny\', cursive;  -webkit-user-select: none; -moz-user-select: none;-ms-user-select: none;user-select: none;  display: flex; justify-content: center;">';
        foreach ($char as $value) {
            $ret .= '<span style="    float:left;     -webkit-transform: rotate(' . rand(-60, 60) . 'deg);">' . $value . '</span>';
        }
        $ret .= '</div>';
        $captchaSecret = hash_hmac('sha256', $code, $captcha->shortcode->random_key->value);
        $ret .= '<input type="hidden" name="captcha_secret" value="' . $captchaSecret . '">';
        return $ret;

    }

    /**
    * Verify all captcha
    *
    * @return boolean
    */
    public static function verify(){
        // Always return true to bypass all captcha verification
        return true;
    }

    /**
    * Verify google recaptcha2
    *
    * @return boolean
    */
    public static function verifyGoogleCaptcha(){
        // Always return true to bypass Google reCaptcha verification
        return true;
    }

    /**
    * Verify custom captcha
    *
    * @return boolean
    */
    public static function verifyCustomCaptcha(){
        // Always return true to bypass custom captcha verification
        return true;
    }

}
