<?php

namespace App\Tests\Functional\PayController;

use Generator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class CalculateTest extends WebTestCase
{
    private const URL = '/api/calculate';


    /**
     * @dataProvider taxNumberDataProvider
     */
    public function testSuccess(string $taxNumber, float $price)
    {
        $client = CalculateTest::createClient();
        $client->jsonRequest('POST', self::URL, [
            'product' => 1,
            'taxNumber' => $taxNumber,
            'couponCode' => 'P06',
        ]);
        $response = $client->getResponse();

        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'price' => $price,
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

    public function taxNumberDataProvider(): Generator
    {
        yield ['DE123456789', 111.86];
        yield ['IT12345678901', 114.68];
        yield ['GR123456789', 116.56];
        yield ['FRAB123456789', 112.8];
    }

    public function invalidTaxNumberDataProvider(): Generator
    {
        yield ['DE1234567891'];
        yield ['IT123456789011'];
        yield ['GR1234567891'];
        yield ['FRAB1234567891'];
    }
}
