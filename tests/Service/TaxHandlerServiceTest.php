<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\TaxBracket;
use App\Repository\TaxBracketRepository;
use App\Service\TaxHandlerService;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TaxHandlerServiceTest extends TestCase
{
    private TaxHandlerService $taxHandlerService;
    private TaxBracketRepository $taxRepository;
    private LoggerInterface $logger;

    private array $taxBrackets;

    protected function setUp(): void
    {
        $this->taxRepository = $this->createMock(TaxBracketRepository::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->taxHandlerService = new TaxHandlerService($this->taxRepository, $this->logger);
        $this->taxBrackets = [
            $this->createTaxBracket('0', '5000', '0'),   // 0% tax on first 5000
            $this->createTaxBracket('5000', '20000', '20'), // 20% tax on 15000
            $this->createTaxBracket('20000', null, '40'),  // 40% tax on anything above 20000
        ];
    }

    public function testCalculateTaxesWithValidIncome(): void
    {
        $this->taxRepository->method('getAllOrdered')->willReturn($this->taxBrackets);

        // Simulate request with income of 40000
        $request = new Request([], [], [], [], [], [], json_encode(['income' => 40000]));

        $response = $this->taxHandlerService->calculateTaxes($request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        // Assertions
        $this->assertEquals(40000, $data['gross_annual_salary']);
        $this->assertEquals(3333.33, $data['gross_monthly_salary']);
        $this->assertEquals(29000, $data['net_annual_salary']);
        $this->assertEquals(2416.66, $data['net_monthly_salary']);
        $this->assertEquals(11000, $data['annual_tax_paid']);
        $this->assertEquals(916.66, $data['monthly_tax_paid']);
    }

    public function testCalculateTaxesReturnsNotFoundWhenNoBrackets(): void
    {
        $this->taxRepository->method('getAllOrdered')->willReturn([]);

        $request = new Request([], [], [], [], [], [], json_encode(['income' => 40000]));

        $response = $this->taxHandlerService->calculateTaxes($request);

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
        $this->assertEquals('No applicable tax brackets found', $response->getContent());
    }

    public function testCalculateTaxesHandlesJsonError(): void
    {
        $request = new Request([], [], [], [], [], [], 'invalid_json');

        $response = $this->taxHandlerService->calculateTaxes($request);

        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
        $this->assertEquals('An error occurred while processing the request', $response->getContent());
    }

    public function testCalculateTaxesWithIncomeExactlyAtBracketBoundary(): void
    {
        $this->taxRepository->method('getAllOrdered')->willReturn($this->taxBrackets);

        $request = new Request([], [], [], [], [], [], json_encode(['income' => 5000]));

        $response = $this->taxHandlerService->calculateTaxes($request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertEquals(5000, $data['gross_annual_salary']);
        $this->assertEquals(5000, $data['net_annual_salary']);
        $this->assertEquals(0, $data['annual_tax_paid']);
    }

    public function testCalculateTaxesWithInvalidIncome(): void
    {
        $this->taxRepository->method('getAllOrdered')->willReturn($this->taxBrackets);

        // Test with missing income field
        $request = new Request([], [], [], [], [], [], json_encode([]));
        $response = $this->taxHandlerService->calculateTaxes($request);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertEquals('Income must be a valid non-negative number', $response->getContent());

        // Test with non-numeric income
        $request = new Request([], [], [], [], [], [], json_encode(['income' => 'abc']));
        $response = $this->taxHandlerService->calculateTaxes($request);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertEquals('Income must be a valid non-negative number', $response->getContent());

        // Test with negative income
        $request = new Request([], [], [], [], [], [], json_encode(['income' => -5000]));
        $response = $this->taxHandlerService->calculateTaxes($request);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertEquals('Income must be a valid non-negative number', $response->getContent());
    }

    private function createTaxBracket(string $minIncome, ?string $maxIncome, string $rate): TaxBracket
    {
        $taxBracket = $this->createMock(TaxBracket::class);
        $taxBracket->method('getMinIncome')->willReturn($minIncome);
        $taxBracket->method('getMaxIncome')->willReturn($maxIncome);
        $taxBracket->method('getRate')->willReturn($rate);

        return $taxBracket;
    }
}
