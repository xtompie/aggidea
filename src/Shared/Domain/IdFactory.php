<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Shared\Domain;

interface IdFactory
{
    public function id(): string;
}
