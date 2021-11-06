<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Shared\Domain;

final class Time
{
    public static function now(): self
    {
        return new self('');
    }

    public function __construct(
        protected string $time
    ) {}

    public function __toString(): string
    {
        return $this->time;
    }
}
