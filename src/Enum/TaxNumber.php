<?php

namespace App\Enum;

enum TaxNumber
{
    case DE;
    case IT;
    case GR;
    case FR;

    public function getTaxNumber(): string
    {
        return match ($this) {
            self::DE => '/^DE\d{9}$/',
            self::IT => '/^IT\d{11}$/',
            self::GR => '/^GR\d{9}$/',
            self::FR => '/^FR\D{2}\d{9}$/',
        };
    }

    public function getTax(): int
    {
        return match ($this) {
            self::DE => 19,
            self::IT => 22,
            self::GR => 24,
            self::FR => 20,
        };
    }

    public function getTaxByTaxNumber(string $taxNumber): ?int
    {
        foreach (self::cases() as $case) {
            if (preg_match($case->getTaxNumber(), $taxNumber)) {
                return $case->getTax();
            }
        }

        return null;
    }
}
