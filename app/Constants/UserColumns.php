<?php

namespace App\Constants;

class UserColumns
{
    public const ID                 = 'id';
    public const NAME               = 'name';
    public const EMAIL              = 'email';
    public const EMAIL_VERIFIED_AT  = 'email_verified_at';
    public const PASSWORD           = 'password';
    public const REMEMBER_TOKEN     = 'remember_token';
    public const CREATED_AT         = 'created_at';
    public const UPDATED_AT         = 'updated_at';

    public static function getFillable(): array
    {
        return [
            self::NAME,
            self::EMAIL,
            self::PASSWORD,
        ];
    }
}
