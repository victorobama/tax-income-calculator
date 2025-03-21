<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TaxBracketRepository;

#[ORM\Entity(repositoryClass: TaxBracketRepository::class)]
#[ORM\Table(name: 'tax_brackets')]
class TaxBracket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string")]
    private string $bandName;

    #[ORM\Column(type: "decimal", precision: 15, scale: 2)]
    private string $minIncome;

    #[ORM\Column(type: "decimal", precision: 15, scale: 2, nullable: true)]
    private ?string $maxIncome;

    #[ORM\Column(type: "decimal", precision: 5, scale: 2)]
    private string $rate;

    #[ORM\Column(type: "string", length: 255)]
    private string $description;

    public function getRate(): string
    {
        return $this->rate;
    }

    public function getMinIncome(): string
    {
        return $this->minIncome;
    }

    public function getMaxIncome(): ?string
    {
        return $this->maxIncome;
    }
}
