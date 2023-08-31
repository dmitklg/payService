<?php

declare(strict_types=1);

namespace App\Payment;

use App\Interface\PaymentInterface;
use App\PaymentProcessors\PaypalPaymentProcessor;
use Exception;

final class PaymentPaypal implements PaymentInterface
{

    public function __construct(private readonly PaypalPaymentProcessor $paymentProcessor)
    {
    }

    public function getType(): string
    {
        return 'paypal';
    }

    /**
     * @throws Exception
     */
    public function pay(float $price): float
    {
        $this->paymentProcessor->pay($realPrice = (int) $price);
        return $realPrice;
    }
}
