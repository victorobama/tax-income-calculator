<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\TaxHandlerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1/taxes')]
class TaxController extends AbstractController
{
    public function __construct(private readonly TaxHandlerService $taxHandlerService)
    {
    }

    #[Route('/calculate', name: 'calculate_taxes', methods: ['POST'])]
    #[Cache(smaxage: 60)]
    public function calculateTaxes(Request $request): Response
    {
        return $this->taxHandlerService->calculateTaxes($request);
    }
}
