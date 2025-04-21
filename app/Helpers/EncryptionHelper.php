<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Crypt;

class EncryptionHelper
{
    public static function encrypt($id)
    {
        return Crypt::encryptString($id);
    }

    public static function decrypt($encryptedId) 
    {
        try {
            return Crypt::decryptString($encryptedId);
        } catch (\Exception $e) {
            abort(404);
        }
    }
}