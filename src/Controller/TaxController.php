<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaxController
{
    #[Route('/api/v1/taxes/calculate', name: 'calculate_taxes', methods: ['POST'])]
    public function calculateTaxes(Request $request): Response
    {
        return new Response(
            content: '{"message": "Taxes calculated"}',
            status: Response::HTTP_OK,
            headers: ['Content-Type' => 'application/json']
        );
    }
}