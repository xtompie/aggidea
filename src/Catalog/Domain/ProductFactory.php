<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Catalog\Domain;

use Xtompie\Aggidea\Shared\Domain\IdGenerator;
use Xtompie\Aggidea\Shared\Domain\Price;

class ProductFactory
{
    public function __construct(
        protected IdGenerator $idGenerator,
    ) {}

    public function create(string $title, Price $price): Product
    {
        return new Product(
            id: $this->idGenerator->id(),
            title: $title,
            price: $price
        );
    }
}