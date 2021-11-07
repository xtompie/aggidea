<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Catalog\Domain;

use Xtompie\Aggidea\Shared\Domain\IdFactory;
use Xtompie\Aggidea\Shared\Domain\Price;

class ProductFactory
{
    public function __construct(
        protected IdFactory $IdFactory,
    ) {}

    public function create(string $title, Price $price): Product
    {
        return new Product(
            id: $this->IdFactory->id(),
            title: $title,
            price: $price
        );
    }
}