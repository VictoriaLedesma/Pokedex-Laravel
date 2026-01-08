<?php

declare(strict_types=1);

namespace App\Application\UseCases;

use App\Application\DTOs\PokemonDTO;
use App\Domain\Repositories\PokemonRepositoryInterface;
use App\Domain\ValueObjects\PokemonId;
use App\Domain\ValueObjects\PokemonName;
use InvalidArgumentException;

/**
 * Get Pokemon Detail Use Case
 * 
 * Handles the business logic for retrieving Pokemon details.
 * Can search by ID or name.
 * Single Responsibility: only handles Pokemon detail retrieval.
 */
final readonly class GetPokemonDetailUseCase
{
    public function __construct(
        private PokemonRepositoryInterface $pokemonRepository
    ) {
    }

    /**
     * Execute the use case by Pokemon ID
     *
     * @param int $id
     * @return PokemonDTO|null
     */
    public function executeById(int $id): ?PokemonDTO
    {
        $pokemonId = new PokemonId($id);
        $pokemon = $this->pokemonRepository->findById($pokemonId);

        if ($pokemon === null) {
            return null;
        }

        return new PokemonDTO(
            id: $pokemon->getId()->value(),
            name: $pokemon->getName()->formatted(),
            imageUrl: $pokemon->getImageUrl(),
            types: array_map(fn($t) => $t->formatted(), $pokemon->getTypes()),
            stats: $pokemon->getStats()->toArray(),
            height: $pokemon->getHeight(),
            weight: $pokemon->getWeight(),
        );
    }

    /**
     * Execute the use case by Pokemon name
     *
     * @param string $name
     * @return PokemonDTO|null
     */
    public function executeByName(string $name): ?PokemonDTO
    {
        $pokemonName = new PokemonName($name);
        $pokemon = $this->pokemonRepository->findByName($pokemonName);

        if ($pokemon === null) {
            return null;
        }

        return new PokemonDTO(
            id: $pokemon->getId()->value(),
            name: $pokemon->getName()->formatted(),
            imageUrl: $pokemon->getImageUrl(),
            types: array_map(fn($t) => $t->formatted(), $pokemon->getTypes()),
            stats: $pokemon->getStats()->toArray(),
            height: $pokemon->getHeight(),
            weight: $pokemon->getWeight(),
        );
    }
}

