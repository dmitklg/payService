<?php

namespace App\Entity;

use App\Enum\CouponType;
use App\Repository\CouponRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CouponRepository::class)]
class Coupon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\Column(type: 'couponType')]
    private ?CouponType $type = null;

    #[ORM\Column]
    private ?float $discount = null;

    public function getType(): ?CouponType
    {
        return $this->type;
    }

    public function getDiscount(): ?float
    {
        return $this->discount;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function setType(?CouponType $type): void
    {
        $this->type = $type;
    }

    public function setDiscount(?float $discount): void
    {
        $this->discount = $discount;
    }
}
