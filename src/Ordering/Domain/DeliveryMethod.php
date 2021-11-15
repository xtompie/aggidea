<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Ordering\Domain;

final class DeliveryMethod
{
    public static function courier(): self
    {
        return new self('courier');
    }

    public static function pickup(): self
    {
        return new self('pickup');
    }

    public function __construct(
        protected string $deliveryMethod,
    ) {}

    public function __toString()
    {
        return $this->deliveryMethod;
    }

    public function equals(DeliveryMethod $deliveryMethod): bool
    {
        return $this->__toString() === $deliveryMethod->__toString();
    }

    public function hasSurcharge(): bool
    {
        return match($this->deliveryMethod) {
            'courier' => true,
            'pickup' => false,
        };
    }
    public function surcharge(): string
    {
        return match($this->deliveryMethod) {
            'courier' => '9,99',
            'pickup' => '',
        };
    }
}