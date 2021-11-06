<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Catalog\Infrastructure;

use PDO;
use Xtompie\Aggidea\Catalog\Domain\Product;
use Xtompie\Aggidea\Catalog\Domain\ProductRepository as DomainProductRepository;
use Xtompie\Aggidea\Shared\Infrastructure\AggregatePresister;
use Xtompie\Aggidea\Shared\Infrastructure\AggregateProvider;

class ProductRepository implements DomainProductRepository, AggregateProvider
{
    public function __construct(
        protected PDO $pdo,
        protected ProductORM $productORM,
        protected AggregatePresister $presister,
    ) {}

    public function findById(string $id): ?Product
    {
        $stmt = $this->pdo->prepare("SELECT * FROM articles WHERE id=:id");
        $stmt->execute(['id' => $id]);
        $tuple = $stmt->fetch();
        if (!$tuple) {
            return null;
        }
        return $this->productORM->model($tuple);
    }

    public function save(Product $product)
    {
        return $this->presister->presist($this, $this->productORM, $product);
    }
}
