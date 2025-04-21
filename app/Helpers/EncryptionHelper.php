<?php

namespace App\Helpers;

class EncryptionHelper
{
    public static function encrypt($id)
    {
        return base64_encode($id);
    }

    public static function decrypt($encryptedId) 
    {
        return base64_decode($encryptedId);
    }
}