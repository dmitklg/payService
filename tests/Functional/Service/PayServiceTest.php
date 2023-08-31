<?php

declare(strict_types=1);

namespace App\Tests\Functional\Service;

use App\Service\PaymentService;
use Exception;
use Generator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class PayServiceTest extends WebTestCase
{
    public function testTooHighPrice()
    {
        /**
         * @var PaymentService $service
         */
        $service = $this->getService();
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Too high price');
        $service->pay('paypal', 100001);
    }

    public function testTooLowPrice()
    {
        /**
         * @var PaymentService $service
         */
        $service = $this->getService();
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Too low price');
        $service->pay('stripe', 9);
    }

    public function getService()
    {
        return $this->getContainer()->get('App\Service\PaymentService');
    }
}
