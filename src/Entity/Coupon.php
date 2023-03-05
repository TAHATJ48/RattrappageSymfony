<?php

namespace App\Entity;

use App\Repository\CouponRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CouponRepository::class)]
class Coupon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $code = null;

    #[ORM\Column(length: 2, nullable: true)]
    private ?string $percentageOff = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getPercentageOff(): ?string
    {
        return $this->percentageOff;
    }

    public function setPercentageOff(?string $percentageOff): self
    {
        $this->percentageOff = $percentageOff;

        return $this;
    }
}
