<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Catalog\Domain;

use Xtompie\Aggidea\Shared\Domain\Price;

class Product
{
    public function __construct(
        protected string $id,
        protected string $title,
        protected Price $price,
    ) {}

    public function id(): string
    {
        return $this->id;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function price(): Price
    {
        return $this->price;
    }

    public function updatePrice(Price $price)
    {
        $this->price = $price;
    }
}