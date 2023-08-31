<?php

declare(strict_types=1);

namespace App\Payment;

use App\Interface\PaymentInterface;
use App\PaymentProcessors\StripePaymentProcessor;
use Exception;

final class PaymentStripe implements PaymentInterface
{

    public function __construct(private readonly StripePaymentProcessor $paymentProcessor)
    {
    }

    public function getType(): string
    {
        return 'stripe';
    }

    /**
     * @throws Exception
     */
    public function pay(float $price): float
    {
        if ($this->paymentProcessor->processPayment($realPrice = (int) $price)) {
            return $realPrice;
        }

        throw new Exception('Too low price');
    }
}
