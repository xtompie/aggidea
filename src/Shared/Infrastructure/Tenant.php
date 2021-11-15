<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Shared\Infrastructure;

interface Tenant
{
    public function id(): string;
}
