<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Shared\Infrastructure;

use ReflectionObject;

final class ObjectBackdoor
{
    protected $___reflection;
    protected $___subject;

    public static function new(object $subject): self
    {
        return new self($subject);
    }

    public function __construct(object $subject)
    {
        $this->___subject = $subject;
        $this->___reflection = new ReflectionObject($subject);
    }

    public function __set($name, $value)
    {
        if (!$this->___reflection->hasProperty($name)) {
            $this->___subject->$name = $value;
            return;
        }

        $property = $this->___reflection->getProperty($name);
        $property->setAccessible(true);
        $property->setValue($this->___subject, $value);
    }

    public function __get($name)
    {
        if (!$this->___reflection->hasProperty($name)) {
            return null;
        }

        $property = $this->___reflection->getProperty($name);
        $property->setAccessible(true);
        return $property->getValue($this->___subject);
    }
}