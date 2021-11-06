<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Shared\Domain;

final class ContactAddress
{
    public function __construct(
        protected string $firstName,
        protected string $lastName,
        protected string $telephone,
        protected string $locality,
        protected string $postalCode,
        protected string $streetName,
        protected string $streetNumber,
    ) {}

    public function firstName(): string
    {
        return $this->firstName;
    }

    public function lastName(): string
    {
        return $this->lastName;
    }

    public function telephone(): string
    {
        return $this->telephone;
    }

    public function locality(): string
    {
        return $this->locality;
    }

    public function postalCode(): string
    {
        return $this->postalCode;
    }

    public function streetName(): string
    {
        return $this->streetName;
    }

    public function streetNumber(): string
    {
        return $this->streetNumber;
    }

}
