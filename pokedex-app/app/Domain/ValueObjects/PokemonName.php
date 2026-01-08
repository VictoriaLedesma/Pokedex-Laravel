<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects;

use InvalidArgumentException;

/**
 * Pokemon Name Value Object
 * 
 * Represents a valid Pokemon name.
 * Immutable and self-validating.
 */
final readonly class PokemonName
{
    private string $value;

    public function __construct(string $value)
    {
        $this->validate($value);
        $this->value = strtolower(trim($value));
    }

    private function validate(string $value): void
    {
        $trimmed = trim($value);
        
        if (empty($trimmed)) {
            throw new InvalidArgumentException(
                'Pokemon name cannot be empty'
            );
        }

        if (strlen($trimmed) > 100) {
            throw new InvalidArgumentException(
                'Pokemon name is too long'
            );
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function formatted(): string
    {
        return ucfirst($this->value);
    }

    public function equals(PokemonName $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}

