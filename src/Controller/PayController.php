<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

#[Route('/api')]
#[OA\Tag(name: 'payService api')]
final class PayController extends AbstractController
{
    #[Route('/calculate', methods: ['GET', 'POST'])] // TODO only POST
    #[OA\Response(
        response: 200,
        description: 'Accepted',
    )]
    #[OA\Response(
        response: 400,
        description: 'Bad request (validation failed)',
    )]
    public function calculate(): JsonResponse
    {
        return $this->json(['result' => 'ok']);
    }
}
