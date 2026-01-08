<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects;

use InvalidArgumentException;

/**
 * Pokemon Type Value Object
 * 
 * Represents a Pokemon type (e.g., fire, water, grass).
 * Immutable and self-validating.
 */
final readonly class PokemonType
{
    private string $value;

    /**
     * Valid Pokemon types with their colors for UI
     */
    private const TYPE_COLORS = [
        'normal' => '#A8A878',
        'fire' => '#F08030',
        'water' => '#6890F0',
        'electric' => '#F8D030',
        'grass' => '#78C850',
        'ice' => '#98D8D8',
        'fighting' => '#C03028',
        'poison' => '#A040A0',
        'ground' => '#E0C068',
        'flying' => '#A890F0',
        'psychic' => '#F85888',
        'bug' => '#A8B820',
        'rock' => '#B8A038',
        'ghost' => '#705898',
        'dragon' => '#7038F8',
        'dark' => '#705848',
        'steel' => '#B8B8D0',
        'fairy' => '#EE99AC',
    ];

    public function __construct(string $value)
    {
        $this->validate($value);
        $this->value = strtolower(trim($value));
    }

    private function validate(string $value): void
    {
        $trimmed = strtolower(trim($value));
        
        if (empty($trimmed)) {
            throw new InvalidArgumentException(
                'Pokemon type cannot be empty'
            );
        }

        if (!array_key_exists($trimmed, self::TYPE_COLORS)) {
            throw new InvalidArgumentException(
                sprintf('Invalid Pokemon type: %s', $value)
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

    public function color(): string
    {
        return self::TYPE_COLORS[$this->value];
    }

    public function equals(PokemonType $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}

