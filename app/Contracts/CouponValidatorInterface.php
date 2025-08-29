<?php

namespace App\Contracts;

interface CouponValidatorInterface
{
    public function validate(string $code): array;
}
