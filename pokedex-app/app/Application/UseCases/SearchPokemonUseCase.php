<?php

declare(strict_types=1);

namespace App\Application\UseCases;

use App\Application\DTOs\PokemonListItemDTO;
use App\Domain\Repositories\PokemonRepositoryInterface;

/**
 * Search Pokemon Use Case
 * 
 * Handles the business logic for searching Pokemon by name.
 * Single Responsibility: only handles Pokemon search.
 */
final readonly class SearchPokemonUseCase
{
    public function __construct(
        private PokemonRepositoryInterface $pokemonRepository
    ) {
    }

    /**
     * Execute the use case
     *
     * @param string $query
     * @return array<PokemonListItemDTO>
     */
    public function execute(string $query): array
    {
        if (empty(trim($query))) {
            return [];
        }

        $pokemon = $this->pokemonRepository->search($query);

        return array_map(
            fn($p) => new PokemonListItemDTO(
                id: $p->getId()->value(),
                name: $p->getName()->formatted(),
                imageUrl: $p->getImageUrl(),
            ),
            $pokemon
        );
    }
}

