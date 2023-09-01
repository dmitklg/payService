<?php

declare(strict_types=1);

namespace App\Request;

use App\Enum\PaymentProcessor;
use App\Validator\ConstraintTaxNumber;
use Happyr\Validator\Constraint\EntityExist;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

final class PayRequest extends BaseRequest
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

    #[Type('string')]
    #[NotBlank]
    #[Choice(callback: 'getPaymentProcessors')]
    protected string $paymentProcessor;


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

    public function getPaymentProcessor(): string
    {
        return $this->paymentProcessor;
    }

    public function getPaymentProcessors(): array
    {
        return PaymentProcessor::getValues();
    }
}
