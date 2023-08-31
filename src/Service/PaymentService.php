<?php

declare(strict_types=1);

namespace App\Service;

use App\Interface\PaymentInterface;
use Exception;

final class PaymentService
{
    /**
     * @param array<PaymentInterface> $paymentProcessors
     */
    public function __construct(private readonly array $paymentProcessors)
    {
    }

    public function pay(string $type, float $price): float
    {
        foreach ($this->paymentProcessors as $paymentProcessor) {
            if ($paymentProcessor->getType() === $type) {
                return $paymentProcessor->pay($price);
            }
        }

        throw new Exception('Invalid payment type');
    }
}
