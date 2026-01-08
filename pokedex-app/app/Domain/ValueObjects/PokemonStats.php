<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects;

use InvalidArgumentException;

/**
 * Pokemon Stats Value Object
 * 
 * Represents Pokemon battle statistics.
 * Immutable and self-validating.
 */
final readonly class PokemonStats
{
    public function __construct(
        private int $hp,
        private int $attack,
        private int $defense,
        private int $specialAttack,
        private int $specialDefense,
        private int $speed,
    ) {
        $this->validate();
    }

    private function validate(): void
    {
        $stats = [
            'HP' => $this->hp,
            'Attack' => $this->attack,
            'Defense' => $this->defense,
            'Special Attack' => $this->specialAttack,
            'Special Defense' => $this->specialDefense,
            'Speed' => $this->speed,
        ];

        foreach ($stats as $name => $value) {
            if ($value < 0 || $value > 255) {
                throw new InvalidArgumentException(
                    sprintf('%s must be between 0 and 255, got %d', $name, $value)
                );
            }
        }
    }

    public function hp(): int
    {
        return $this->hp;
    }

    public function attack(): int
    {
        return $this->attack;
    }

    public function defense(): int
    {
        return $this->defense;
    }

    public function specialAttack(): int
    {
        return $this->specialAttack;
    }

    public function specialDefense(): int
    {
        return $this->specialDefense;
    }

    public function speed(): int
    {
        return $this->speed;
    }

    public function total(): int
    {
        return $this->hp 
            + $this->attack 
            + $this->defense 
            + $this->specialAttack 
            + $this->specialDefense 
            + $this->speed;
    }

    /**
     * @return array<string, int>
     */
    public function toArray(): array
    {
        return [
            'hp' => $this->hp,
            'attack' => $this->attack,
            'defense' => $this->defense,
            'specialAttack' => $this->specialAttack,
            'specialDefense' => $this->specialDefense,
            'speed' => $this->speed,
            'total' => $this->total(),
        ];
    }
}

