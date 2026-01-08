<?php

declare(strict_types=1);

namespace App\Application\DTOs;

/**
 * Pokemon List Item Data Transfer Object
 * 
 * Simplified DTO for Pokemon list views.
 * Contains only essential information for listing.
 */
final readonly class PokemonListItemDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $imageUrl,
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
        ];
    }
}

