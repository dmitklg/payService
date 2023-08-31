<?php

namespace App\Interface;

interface PaymentInterface
{
    public function getType(): string;
    public function pay(float $price): float;
}
