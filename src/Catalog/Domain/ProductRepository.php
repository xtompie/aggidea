<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Catalog\Domain;

interface ProductRepository
{
    public function findById(string $id): ?Product;
    public function save(Product $product): Product;
}
