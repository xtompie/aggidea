<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Catalog\Infrastructure;

use PDO;
use Xtompie\Aggidea\Catalog\Domain\Product;
use Xtompie\Aggidea\Catalog\Domain\ProductRepository as DomainProductRepository;
use Xtompie\Aggidea\Shared\Infrastructure\EntityManager;

class ProductRepository implements DomainProductRepository
{
    public function __construct(
        protected PDO $pdo,
        protected ProductMapper $productMapper,
        protected EntityManager $entityManager,
    ) {}

    public function findById(string $id): ?Product
    {
        $stmt = $this->pdo->prepare("SELECT * FROM articles WHERE id=:id");
        $stmt->execute(['id' => $id]);
        $tuple = $stmt->fetch();
        if (!$tuple) {
            return null;
        }
        return $this->entityManager->load($tuple, $this->productMapper);
    }

    public function save(Product $product): Product
    {
        return $this->entityManager->save($product, $this->productMapper);
    }
}
