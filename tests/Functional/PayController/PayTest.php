<?php

declare(strict_types=1);

namespace App\Tests\Functional\PayController;

use Generator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class PayTest extends WebTestCase
{
    private const URL = '/api/pay';

    /**
     * @dataProvider taxNumberDataProvider
     */
    public function testSuccess(string $taxNumber, float $price)
    {
        $client = PayTest::createClient();
        $client->jsonRequest('POST', self::URL, [
            'product' => 1,
            'taxNumber' => $taxNumber,
            'couponCode' => 'P06',
            'paymentProcessor' => 'paypal',
        ]);
        $response = $client->getResponse();

        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'price' => $price,
                'message' => 'payment successful',
            ]),
            $response->getContent()
        );
    }

    /**
     * @dataProvider invalidTaxNumberDataProvider
     */
    public function testInvalidTaxNumber(string $taxNumber)
    {
        $client = CalculateTest::createClient();
        $client->jsonRequest('POST', self::URL, [
            'product' => 1,
            'taxNumber' => $taxNumber,
            'couponCode' => 'P06',
            'paymentProcessor' => 'paypal',
        ]);
        $response = $client->getResponse();

        $this->assertResponseStatusCodeSame(400);
        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'errors' => [
                    [
                        'message' => 'Invalid Tax Number.',
                        'property' => 'taxNumber',
                        'value' => $taxNumber,
                    ],
                ],
                'message' => 'validation_failed',
            ]),
            $response->getContent()
        );
    }

    public function testValidateAll()
    {
        $client = static::createClient();
        $client->jsonRequest('POST', self::URL, []);
        $response = $client->getResponse();

        $this->assertResponseStatusCodeSame(400);
        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'errors' => [
                    [
                        'message' => 'This value should not be blank.',
                        'property' => 'product',
                        'value' => null,
                    ],
                    [
                        'message' => 'This value should not be blank.',
                        'property' => 'taxNumber',
                        'value' => null,
                    ],
                    [
                        'message' => 'This value should not be blank.',
                        'property' => 'paymentProcessor',
                        'value' => null,
                    ],
                ],
                'message' => 'validation_failed',
            ]),
            $response->getContent()
        );
    }

    public function testNotExistsProduct()
    {
        $client = static::createClient();
        $client->jsonRequest('POST', self::URL, [
            'product' => 100,
            'taxNumber' => 'DE123456789',
            'couponCode' => 'P06',
            'paymentProcessor' => 'paypal',
        ]);
        $response = $client->getResponse();

        $this->assertResponseStatusCodeSame(400);
        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'errors' => [
                    [
                        'message' => 'Entity "App\\Entity\\Product" with property "id": "100" does not exist.',
                        'property' => 'product',
                        'value' => 100,
                    ],
                ],
                'message' => 'validation_failed',
            ]),
            $response->getContent()
        );
    }

    public function testNotExistsCoupon()
    {
        $client = static::createClient();
        $client->jsonRequest('POST', self::URL, [
            'product' => 1,
            'taxNumber' => 'DE123456789',
            'couponCode' => 'P22',
            'paymentProcessor' => 'paypal',
        ]);
        $response = $client->getResponse();

        $this->assertResponseStatusCodeSame(400);
        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'errors' => [
                    [
                        'message' => 'Entity "App\\Entity\\Coupon" with property "code": "P22" does not exist.',
                        'property' => 'couponCode',
                        'value' => 'P22',
                    ],
                ],
                'message' => 'validation_failed',
            ]),
            $response->getContent()
        );
    }

    public function testVeryHighDiscount()
    {
        $client = CalculateTest::createClient();
        $client->jsonRequest('POST', self::URL, [
            'product' => 1,
            'taxNumber' => 'DE123456789',
            'couponCode' => 'F110',
            'paymentProcessor' => 'paypal',
        ]);
        $response = $client->getResponse();

        $this->assertResponseStatusCodeSame(400);
        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'error' => 'Very high discount',
                'message' => 'calculate error',
            ]),
            $response->getContent()
        );
    }

    /**
     * @dataProvider validPaymentProcessorDataProvider
     */
    public function testValidPaymentProcessor(string $paymentProcessor)
    {
        $client = static::createClient();
        $client->jsonRequest('POST', self::URL, [
            'product' => 1,
            'taxNumber' => 'DE123456789',
            'couponCode' => 'P06',
            'paymentProcessor' => $paymentProcessor,
        ]);

        $this->assertResponseStatusCodeSame(200);
    }

    /**
     * @dataProvider invalidPaymentProcessorDataProvider
     */
    public function testInvalidPaymentProcessor(string $paymentProcessor)
    {
        $client = static::createClient();
        $client->jsonRequest('POST', self::URL, [
            'product' => 1,
            'taxNumber' => 'DE123456789',
            'couponCode' => 'P06',
            'paymentProcessor' => $paymentProcessor,
        ]);
        $response = $client->getResponse();

        $this->assertResponseStatusCodeSame(400);
        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'errors' => [
                    [
                        'message' => 'The value you selected is not a valid choice.',
                        'property' => 'paymentProcessor',
                        'value' => $paymentProcessor,
                    ],
                ],
                'message' => 'validation_failed',
            ]),
            $response->getContent()
        );
    }

    public function taxNumberDataProvider(): Generator
    {
        yield ['DE123456789', 111];
        yield ['IT12345678901', 114];
        yield ['GR123456789', 116];
        yield ['FRAB123456789', 112];
    }

    public function invalidTaxNumberDataProvider(): Generator
    {
        yield ['DE1234567891'];
        yield ['IT123456789011'];
        yield ['GR1234567891'];
        yield ['FRAB1234567891'];
    }

    public function validPaymentProcessorDataProvider(): Generator
    {
        yield ['paypal'];
        yield ['stripe'];
    }

    public function invalidPaymentProcessorDataProvider(): Generator
    {
        yield ['paypal1'];
        yield ['stripe1'];
    }
}
