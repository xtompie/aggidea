<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Shared\Domain;

interface IdGenerator
{
    public function id(): string;
}
