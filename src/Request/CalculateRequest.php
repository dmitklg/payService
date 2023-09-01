<?php

declare(strict_types=1);

namespace App\Request;

use App\Validator\ConstraintTaxNumber;
use Happyr\Validator\Constraint\EntityExist;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

final class CalculateRequest extends BaseRequest
{
    #[Type('integer')]
    #[NotBlank]
    #[EntityExist(entity: 'App\Entity\Product')]
    protected int $product;

    #[Type('string')]
    #[NotBlank]
    #[ConstraintTaxNumber]
    protected string $taxNumber;

    #[Type('string')]
    #[EntityExist(entity: 'App\Entity\Coupon', property: 'code')]
    protected ?string $couponCode = null;

    public function getProductId(): int
    {
        return $this->product;
    }

    public function getTaxNumber(): string
    {
        return $this->taxNumber;
    }

    public function getCouponCode(): ?string
    {
        return $this->couponCode;
    }
}
