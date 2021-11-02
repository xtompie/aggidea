<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Shared\Infrastructure;

use Ramsey\Uuid\Uuid;
use Xtompie\Aggidea\Shared\Domain\IdGenerator as DomainIdGenerator;

class IdGenerator implements DomainIdGenerator
{
    public function id(): string
    {
        return (string)Uuid::uuid4();
    }
}
