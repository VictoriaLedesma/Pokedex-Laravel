<?php

declare(strict_types=1);

namespace App\Infrastructure\Services;

use App\Domain\Entities\Pokemon;
use App\Domain\ValueObjects\PokemonId;
use App\Domain\ValueObjects\PokemonName;
use App\Domain\ValueObjects\PokemonType;
use App\Domain\ValueObjects\PokemonStats;
use InvalidArgumentException;

/**
 * Pokemon Mapper
 * 
 * Maps API responses to Domain entities.
 * Single Responsibility: data transformation from API to Domain.
 * Isolates the domain from external data formats.
 */
final class PokemonMapper
{
    /**
     * Map API response to Pokemon entity
     *
     * @param array<string, mixed> $data
     * @return Pokemon
     */
    public function mapToPokemon(array $data): Pokemon
    {
        $this->validateApiData($data);

        return new Pokemon(
            id: new PokemonId($data['id']),
            name: new PokemonName($data['name']),
            imageUrl: $this->extractImageUrl($data),
            types: $this->extractTypes($data),
            stats: $this->extractStats($data),
            height: $this->convertHeight($data['height']),
            weight: $this->convertWeight($data['weight']),
        );
    }

    /**
     * Validate that API data has required fields
     *
     * @param array<string, mixed> $data
     * @return void
     */
    private function validateApiData(array $data): void
    {
        $required = ['id', 'name', 'sprites', 'types', 'stats', 'height', 'weight'];
        
        foreach ($required as $field) {
            if (!isset($data[$field])) {
                throw new InvalidArgumentException(
                    "API response missing required field: {$field}"
                );
            }
        }
    }

    /**
     * Extract image URL from sprites
     *
     * @param array<string, mixed> $data
     * @return string
     */
    private function extractImageUrl(array $data): string
    {
        // Prefer official artwork, fallback to front default
        return $data['sprites']['other']['official-artwork']['front_default']
            ?? $data['sprites']['front_default']
            ?? '';
    }

    /**
     * Extract and map Pokemon types
     *
     * @param array<string, mixed> $data
     * @return array<PokemonType>
     */
    private function extractTypes(array $data): array
    {
        if (!isset($data['types']) || !is_array($data['types'])) {
            return [];
        }

        $types = [];
        foreach ($data['types'] as $typeData) {
            if (isset($typeData['type']['name'])) {
                try {
                    $types[] = new PokemonType($typeData['type']['name']);
                } catch (InvalidArgumentException $e) {
                    // Skip invalid types
                    continue;
                }
            }
        }

        return $types;
    }

    /**
     * Extract and map Pokemon stats
     *
     * @param array<string, mixed> $data
     * @return PokemonStats
     */
    private function extractStats(array $data): PokemonStats
    {
        if (!isset($data['stats']) || !is_array($data['stats'])) {
            throw new InvalidArgumentException('Stats data is missing or invalid');
        }

        $statsMap = [];
        foreach ($data['stats'] as $statData) {
            $name = $statData['stat']['name'] ?? '';
            $value = $statData['base_stat'] ?? 0;
            $statsMap[$name] = $value;
        }

        return new PokemonStats(
            hp: $statsMap['hp'] ?? 0,
            attack: $statsMap['attack'] ?? 0,
            defense: $statsMap['defense'] ?? 0,
            specialAttack: $statsMap['special-attack'] ?? 0,
            specialDefense: $statsMap['special-defense'] ?? 0,
            speed: $statsMap['speed'] ?? 0,
        );
    }

    /**
     * Convert height from decimeters to meters
     *
     * @param int $heightInDecimeters
     * @return float
     */
    private function convertHeight(int $heightInDecimeters): float
    {
        return $heightInDecimeters / 10;
    }

    /**
     * Convert weight from hectograms to kilograms
     *
     * @param int $weightInHectograms
     * @return float
     */
    private function convertWeight(int $weightInHectograms): float
    {
        return $weightInHectograms / 10;
    }
}

