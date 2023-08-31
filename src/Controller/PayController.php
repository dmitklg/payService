<?php

declare(strict_types=1);

namespace App\Controller;

use App\Request\CalculateRequest;
use App\Service\CalculateService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;


#[Route('/api')]
#[OA\Tag(name: 'payService api')]
final class PayController extends AbstractController
{
    #[Route('/calculate', methods: ['POST'])]
    #[OA\RequestBody(
        description: 'Input data format',
        required: true,
        content: new OA\JsonContent(
            required: ['id'],
            properties: [
                new OA\Property(property: 'product', description: 'Product ID', type: 'integer', example: 1),
                new OA\Property(property: 'taxNumber', description: 'Tax Number', type: 'string', example: 'GR123456789'),
                new OA\Property(property: 'couponCode', description: 'Coupon Code', type: 'string', example: 'P06'),
            ],
            type: 'object',
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'OK',
    )]
    #[OA\Response(
        response: 400,
        description: 'Bad request (validation failed)',
    )]
    public function calculate(CalculateRequest $request, CalculateService $calculateService): JsonResponse
    {
        if ($messages = $request->validate()) {
            return $this->json($messages, 400);
        }

        return $this->json([
            'price' => $calculateService->calculate(
                $request->getProductId(),
                $request->getTaxNumber(),
                $request->getCouponCode(),
            ),
        ]);
    }
}
