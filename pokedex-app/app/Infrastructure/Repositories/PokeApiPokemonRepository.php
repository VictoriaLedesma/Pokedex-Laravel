<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Pokemon;
use App\Domain\Repositories\PokemonRepositoryInterface;
use App\Domain\ValueObjects\PokemonId;
use App\Domain\ValueObjects\PokemonName;
use App\Infrastructure\Services\PokeApiClient;
use App\Infrastructure\Services\PokemonMapper;
use Illuminate\Support\Facades\Log;

/**
 * PokéAPI Pokemon Repository Implementation
 * 
 * Implements the repository interface using PokéAPI as data source.
 * Single Responsibility: Pokemon data access through PokéAPI.
 * Depends on abstractions through dependency injection.
 */
final class PokeApiPokemonRepository implements PokemonRepositoryInterface
{
    public function __construct(
        private readonly PokeApiClient $apiClient,
        private readonly PokemonMapper $mapper,
    ) {
    }

    /**
     * Find a Pokemon by its ID
     *
     * @param PokemonId $id
     * @return Pokemon|null
     */
    public function findById(PokemonId $id): ?Pokemon
    {
        try {
            $data = $this->apiClient->getPokemonById($id->value());
            
            if ($data === null) {
                return null;
            }

            return $this->mapper->mapToPokemon($data);
        } catch (\Exception $e) {
            Log::error('Failed to find Pokemon by ID', [
                'id' => $id->value(),
                'error' => $e->getMessage(),
            ]);
            
            return null;
        }
    }

    /**
     * Find a Pokemon by its name
     *
     * @param PokemonName $name
     * @return Pokemon|null
     */
    public function findByName(PokemonName $name): ?Pokemon
    {
        try {
            $data = $this->apiClient->getPokemonByName($name->value());
            
            if ($data === null) {
                return null;
            }

            return $this->mapper->mapToPokemon($data);
        } catch (\Exception $e) {
            Log::error('Failed to find Pokemon by name', [
                'name' => $name->value(),
                'error' => $e->getMessage(),
            ]);
            
            return null;
        }
    }

    /**
     * Get a list of Pokemon with pagination
     *
     * @param int $limit
     * @param int $offset
     * @return array<Pokemon>
     */
    public function list(int $limit = 20, int $offset = 0): array
    {
        try {
            $listData = $this->apiClient->getPokemonList($limit, $offset);
            
            Log::info('API List Response', ['has_data' => $listData !== null, 'has_results' => isset($listData['results']) ? count($listData['results']) : 0]);
            
            if ($listData === null || !isset($listData['results'])) {
                Log::warning('No results from API');
                return [];
            }

            $pokemon = [];
            foreach ($listData['results'] as $item) {
                if (!isset($item['name'])) {
                    continue;
                }

                try {
                    Log::info('Fetching Pokemon', ['name' => $item['name']]);
                    $pokemonData = $this->apiClient->getPokemonByName($item['name']);
                    if ($pokemonData !== null) {
                        $pokemon[] = $this->mapper->mapToPokemon($pokemonData);
                        Log::info('Pokemon loaded successfully', ['name' => $item['name']]);
                    }
                } catch (\Exception $e) {
                    // Skip failed Pokemon and continue
                    Log::warning('Failed to load Pokemon in list', [
                        'name' => $item['name'],
                        'error' => $e->getMessage(),
                    ]);
                    continue;
                }
            }

            Log::info('Total Pokemon loaded', ['count' => count($pokemon)]);
            return $pokemon;
        } catch (\Exception $e) {
            Log::error('Failed to list Pokemon', [
                'limit' => $limit,
                'offset' => $offset,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [];
        }
    }

    /**
     * Search Pokemon by name pattern
     * 
     * Note: PokéAPI doesn't have a search endpoint, so we try exact match
     * or numeric ID match.
     *
     * @param string $query
     * @return array<Pokemon>
     */
    public function search(string $query): array
    {
        $results = [];
        
        Log::info('Searching Pokemon', ['query' => $query]);

        // Try by ID first if numeric
        if (is_numeric($query)) {
            try {
                Log::info('Trying search by ID', ['id' => (int) $query]);
                $pokemon = $this->findById(new PokemonId((int) $query));
                if ($pokemon !== null) {
                    Log::info('Found by ID', ['id' => (int) $query]);
                    $results[] = $pokemon;
                    return $results;
                }
            } catch (\Exception $e) {
                Log::warning('Search by ID failed', ['id' => $query, 'error' => $e->getMessage()]);
            }
        }

        // Try by name
        try {
            Log::info('Trying search by name', ['name' => $query]);
            $pokemon = $this->findByName(new PokemonName($query));
            if ($pokemon !== null) {
                Log::info('Found by name', ['name' => $query]);
                $results[] = $pokemon;
                return $results;
            } else {
                Log::warning('Pokemon not found by name', ['name' => $query]);
            }
        } catch (\Exception $e) {
            Log::error('Search by name failed', ['name' => $query, 'error' => $e->getMessage()]);
        }

        Log::info('Search completed', ['query' => $query, 'results' => count($results)]);
        return $results;
    }
}

