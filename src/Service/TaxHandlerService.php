<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\CalculateTaxesResponseDto;
use App\Repository\TaxBracketRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

readonly class TaxHandlerService
{
    public function __construct(private TaxBracketRepository $taxRepository, private LoggerInterface $logger)
    {
    }

    public function calculateTaxes(Request $request): Response
    {
        try {
            $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
            $income = $data['income'] ?? null;

            if (empty($income) || !is_numeric($income) || $income < 0) {
                $this->logger->warning("Invalid income value", ['income' => $income]);
                return $this->buildResponse('Income must be a valid non-negative number', Response::HTTP_BAD_REQUEST);
            }

            $taxBrackets = $this->taxRepository->getAllOrdered();
            if (empty($taxBrackets)) {
                $this->logger->warning("No applicable tax brackets found", ['income' => $income]);
                return $this->buildResponse('No applicable tax brackets found', Response::HTTP_NOT_FOUND);
            }

            $incomeValues = $this->calculate((string)$income, $taxBrackets);
            $response = $this->buildResponse(json_encode($incomeValues->toArray(), JSON_THROW_ON_ERROR), Response::HTTP_OK);
        } catch (\JsonException $exception) {
            $this->logger->error("JSON processing error", [
                'income' => $income ?? 'unknown',
                'error' => $exception->getMessage()
            ]);
            $response = $this->buildResponse(
                'An error occurred while processing the request',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        } catch (\Exception $exception) {
            $this->logger->error("Unexpected error in tax calculation", [
                'income' => $income ?? 'unknown',
                'error' => $exception->getMessage()
            ]);
            $response = $this->buildResponse(
                'An error occurred while processing the request',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return $response;
    }

    /**
     * @param string $income
     * @param array $taxBrackets
     * @return CalculateTaxesResponseDto
     */
    private function calculate(string $income, array $taxBrackets): CalculateTaxesResponseDto
    {
        $remainingIncome = (float)$income;
        $annualTaxPaid = '0';

        foreach ($taxBrackets as $taxBracket) {
            $minIncome = $taxBracket->getMinIncome();
            $maxIncome = $taxBracket->getMaxIncome() ?? (string) PHP_INT_MAX;
            $taxableIncome = min($remainingIncome, (float) bcsub($maxIncome, $minIncome, 2));
            $taxRate = bcdiv($taxBracket->getRate(), '100', 2);
            $annualTaxPaid = bcadd($annualTaxPaid, bcmul((string)$taxableIncome, $taxRate, 4), 4);
            $remainingIncome -= $taxableIncome;
        }

        return new CalculateTaxesResponseDto(
            grossAnnualSalary: $income,
            grossMonthlySalary: bcdiv($income, '12', 2),
            netAnnualSalary: bcsub($income, $annualTaxPaid, 2),
            netMonthlySalary: bcdiv(bcsub($income, $annualTaxPaid, 4), '12', 2),
            annualTaxPaid: bcsub($annualTaxPaid, '0', 2),
            monthlyTaxPaid: bcdiv($annualTaxPaid, '12', 2)
        );
    }

    private function buildResponse($content, int $status): Response
    {
        return new Response(
            content: is_string($content) ? $content : json_encode($content, JSON_THROW_ON_ERROR),
            status: $status,
            headers: ['Content-Type' => 'application/json']
        );
    }
}