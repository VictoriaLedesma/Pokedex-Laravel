<?php

declare(strict_types=1);

namespace App\Application\UseCases;

use App\Application\DTOs\PokemonListItemDTO;
use App\Domain\Repositories\PokemonRepositoryInterface;

/**
 * List Pokemon Use Case
 * 
 * Handles the business logic for listing Pokemon.
 * Single Responsibility: only handles Pokemon listing.
 * Depends on abstraction (PokemonRepositoryInterface), not implementation.
 */
final readonly class ListPokemonUseCase
{
    public function __construct(
        private PokemonRepositoryInterface $pokemonRepository
    ) {
    }

    /**
     * Execute the use case
     *
     * @param int $limit
     * @param int $offset
     * @return array<PokemonListItemDTO>
     */
    public function execute(int $limit = 20, int $offset = 0): array
    {
        $pokemon = $this->pokemonRepository->list($limit, $offset);

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

