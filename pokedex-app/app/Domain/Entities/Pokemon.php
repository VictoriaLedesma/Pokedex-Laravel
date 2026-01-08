<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use App\Domain\ValueObjects\PokemonId;
use App\Domain\ValueObjects\PokemonName;
use App\Domain\ValueObjects\PokemonType;
use App\Domain\ValueObjects\PokemonStats;

/**
 * Pokemon Entity
 * 
 * Represents a Pokemon with all its properties.
 * This is a domain entity following DDD principles.
 * Immutable by design to ensure data integrity.
 */
final readonly class Pokemon
{
    /**
     * @param PokemonId $id
     * @param PokemonName $name
     * @param string $imageUrl
     * @param array<PokemonType> $types
     * @param PokemonStats $stats
     * @param float $height Height in meters
     * @param float $weight Weight in kilograms
     */
    public function __construct(
        private PokemonId $id,
        private PokemonName $name,
        private string $imageUrl,
        private array $types,
        private PokemonStats $stats,
        private float $height,
        private float $weight,
    ) {
    }

    public function getId(): PokemonId
    {
        return $this->id;
    }

    public function getName(): PokemonName
    {
        return $this->name;
    }

    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    /**
     * @return array<PokemonType>
     */
    public function getTypes(): array
    {
        return $this->types;
    }

    public function getStats(): PokemonStats
    {
        return $this->stats;
    }

    public function getHeight(): float
    {
        return $this->height;
    }

    public function getWeight(): float
    {
        return $this->weight;
    }

    /**
     * Get formatted height in meters
     */
    public function getFormattedHeight(): string
    {
        return number_format($this->height, 1) . ' m';
    }

    /**
     * Get formatted weight in kilograms
     */
    public function getFormattedWeight(): string
    {
        return number_format($this->weight, 1) . ' kg';
    }

    /**
     * Convert to array for serialization
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id->value(),
            'name' => $this->name->value(),
            'imageUrl' => $this->imageUrl,
            'types' => array_map(fn(PokemonType $type) => $type->value(), $this->types),
            'stats' => $this->stats->toArray(),
            'height' => $this->height,
            'weight' => $this->weight,
        ];
    }
}

