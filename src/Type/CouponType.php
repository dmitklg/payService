<?php

declare(strict_types=1);

namespace App\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use App\Enum\CouponType as CouponTypeEnum;

final class CouponType extends StringType
{
    public const NAME = 'couponType';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof CouponTypeEnum ? $value->value : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?CouponTypeEnum
    {
        return !empty($value) ? CouponTypeEnum::tryFrom($value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
