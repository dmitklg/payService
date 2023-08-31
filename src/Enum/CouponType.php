<?php

namespace App\Enum;

enum CouponType: string
{
    case FIXED = 'fixed';
    case PROCENT = 'procent';

    public function isFixedType(): bool
    {
        return $this === self::FIXED;
    }
}
