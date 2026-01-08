<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\Pokemon;
use App\Domain\ValueObjects\PokemonId;
use App\Domain\ValueObjects\PokemonName;

/**
 * Pokemon Repository Interface
 * 
 * Defines the contract for Pokemon data access.
 * Following Dependency Inversion Principle: depend on abstractions, not concretions.
 */
interface PokemonRepositoryInterface
{
    /**
     * Find a Pokemon by its ID
     *
     * @param PokemonId $id
     * @return Pokemon|null
     */
    public function findById(PokemonId $id): ?Pokemon;

    /**
     * Find a Pokemon by its name
     *
     * @param PokemonName $name
     * @return Pokemon|null
     */
    public function findByName(PokemonName $name): ?Pokemon;

    /**
     * Get a list of Pokemon with pagination
     *
     * @param int $limit
     * @param int $offset
     * @return array<Pokemon>
     */
    public function list(int $limit = 20, int $offset = 0): array;

    /**
     * Search Pokemon by name pattern
     *
     * @param string $query
     * @return array<Pokemon>
     */
    public function search(string $query): array;
}

