<?php

declare(strict_types=1);

namespace App\Dto;

class CalculateTaxesResponseDto
{
    public function __construct(
        public string $grossAnnualSalary,
        public string $grossMonthlySalary,
        public string $netAnnualSalary,
        public string $netMonthlySalary,
        public string $annualTaxPaid,
        public string $monthlyTaxPaid
    ) {}

    public function toArray(): array
    {
        return [
            'gross_annual_salary' => $this->grossAnnualSalary,
            'gross_monthly_salary' => $this->grossMonthlySalary,
            'net_annual_salary' => $this->netAnnualSalary,
            'net_monthly_salary' => $this->netMonthlySalary,
            'annual_tax_paid' => $this->annualTaxPaid,
            'monthly_tax_paid' => $this->monthlyTaxPaid,
        ];
    }

}