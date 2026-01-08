<?php

declare(strict_types=1);

namespace App\Application\DTOs;

/**
 * Pokemon Data Transfer Object
 * 
 * Used to transfer Pokemon data between layers.
 * Keeps layers decoupled and allows different representations.
 */
final readonly class PokemonDTO
{
    /**
     * @param int $id
     * @param string $name
     * @param string $imageUrl
     * @param array<string> $types
     * @param array<string, int> $stats
     * @param float $height
     * @param float $weight
     */
    public function __construct(
        public int $id,
        public string $name,
        public string $imageUrl,
        public array $types,
        public array $stats,
        public float $height,
        public float $weight,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'imageUrl' => $this->imageUrl,
            'types' => $this->types,
            'stats' => $this->stats,
            'height' => $this->height,
            'weight' => $this->weight,
        ];
    }
}

