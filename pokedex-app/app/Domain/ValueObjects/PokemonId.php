<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects;

use InvalidArgumentException;

/**
 * Pokemon ID Value Object
 * 
 * Represents a valid Pokemon ID.
 * Immutable and self-validating.
 */
final readonly class PokemonId
{
    private int $value;

    public function __construct(int $value)
    {
        $this->validate($value);
        $this->value = $value;
    }

    private function validate(int $value): void
    {
        if ($value < 1) {
            throw new InvalidArgumentException(
                'Pokemon ID must be a positive integer'
            );
        }
    }

    public function value(): int
    {
        return $this->value;
    }

    public function equals(PokemonId $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}

