<?php

declare(strict_types=1);

namespace App\Service;

use App\Enum\CouponType;
use App\Enum\TaxNumber;
use App\Repository\CouponRepository;
use App\Repository\ProductRepository;

final class CalculateService
{

    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly CouponRepository $couponRepository,
    )
    {
    }

    public function calculate(int $productId, string $taxNumber, ?string $couponCode): float
    {
        $product = $this->productRepository->find($productId);
        $tax = TaxNumber::getTaxByTaxNumber($taxNumber);
        $price = $product->getPrice();

        if ($couponCode) {
            $coupon = $this->couponRepository->findByCode($couponCode);
            $price = match ($coupon->getType()) {
                CouponType::FIXED => $price - $coupon->getDiscount(),
                CouponType::PROCENT => $price * ((100 - $coupon->getDiscount()) / 100),
            };
        }

        $price += $price * $tax / 100;

        return $price;
    }
}
