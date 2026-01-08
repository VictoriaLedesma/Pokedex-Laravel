<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Services;

use App\Infrastructure\Services\PokemonMapper;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Pokemon Mapper Tests
 * 
 * Tests the mapping from API responses to Domain entities.
 */
final class PokemonMapperTest extends TestCase
{
    private PokemonMapper $mapper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mapper = new PokemonMapper();
    }

    public function test_maps_valid_api_response_to_pokemon_entity(): void
    {
        $apiData = [
            'id' => 25,
            'name' => 'pikachu',
            'sprites' => [
                'other' => [
                    'official-artwork' => [
                        'front_default' => 'https://example.com/pikachu.png'
                    ]
                ],
                'front_default' => 'https://example.com/pikachu-sprite.png'
            ],
            'types' => [
                ['type' => ['name' => 'electric']]
            ],
            'stats' => [
                ['stat' => ['name' => 'hp'], 'base_stat' => 35],
                ['stat' => ['name' => 'attack'], 'base_stat' => 55],
                ['stat' => ['name' => 'defense'], 'base_stat' => 40],
                ['stat' => ['name' => 'special-attack'], 'base_stat' => 50],
                ['stat' => ['name' => 'special-defense'], 'base_stat' => 50],
                ['stat' => ['name' => 'speed'], 'base_stat' => 90],
            ],
            'height' => 4,  // 0.4 meters
            'weight' => 60, // 6.0 kg
        ];

        $pokemon = $this->mapper->mapToPokemon($apiData);

        $this->assertSame(25, $pokemon->getId()->value());
        $this->assertSame('pikachu', $pokemon->getName()->value());
        $this->assertSame('https://example.com/pikachu.png', $pokemon->getImageUrl());
        $this->assertCount(1, $pokemon->getTypes());
        $this->assertSame('electric', $pokemon->getTypes()[0]->value());
        $this->assertSame(35, $pokemon->getStats()->hp());
        $this->assertSame(0.4, $pokemon->getHeight());
        $this->assertSame(6.0, $pokemon->getWeight());
    }

    public function test_throws_exception_when_required_field_missing(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('API response missing required field');

        $incompleteData = [
            'id' => 25,
            'name' => 'pikachu',
            // Missing other required fields
        ];

        $this->mapper->mapToPokemon($incompleteData);
    }

    public function test_uses_fallback_sprite_when_official_artwork_missing(): void
    {
        $apiData = [
            'id' => 25,
            'name' => 'pikachu',
            'sprites' => [
                'front_default' => 'https://example.com/pikachu-sprite.png'
            ],
            'types' => [
                ['type' => ['name' => 'electric']]
            ],
            'stats' => [
                ['stat' => ['name' => 'hp'], 'base_stat' => 35],
                ['stat' => ['name' => 'attack'], 'base_stat' => 55],
                ['stat' => ['name' => 'defense'], 'base_stat' => 40],
                ['stat' => ['name' => 'special-attack'], 'base_stat' => 50],
                ['stat' => ['name' => 'special-defense'], 'base_stat' => 50],
                ['stat' => ['name' => 'speed'], 'base_stat' => 90],
            ],
            'height' => 4,
            'weight' => 60,
        ];

        $pokemon = $this->mapper->mapToPokemon($apiData);

        $this->assertSame('https://example.com/pikachu-sprite.png', $pokemon->getImageUrl());
    }

    public function test_handles_multiple_types(): void
    {
        $apiData = [
            'id' => 6,
            'name' => 'charizard',
            'sprites' => [
                'front_default' => 'https://example.com/charizard.png'
            ],
            'types' => [
                ['type' => ['name' => 'fire']],
                ['type' => ['name' => 'flying']]
            ],
            'stats' => [
                ['stat' => ['name' => 'hp'], 'base_stat' => 78],
                ['stat' => ['name' => 'attack'], 'base_stat' => 84],
                ['stat' => ['name' => 'defense'], 'base_stat' => 78],
                ['stat' => ['name' => 'special-attack'], 'base_stat' => 109],
                ['stat' => ['name' => 'special-defense'], 'base_stat' => 85],
                ['stat' => ['name' => 'speed'], 'base_stat' => 100],
            ],
            'height' => 17,
            'weight' => 905,
        ];

        $pokemon = $this->mapper->mapToPokemon($apiData);

        $this->assertCount(2, $pokemon->getTypes());
        $this->assertSame('fire', $pokemon->getTypes()[0]->value());
        $this->assertSame('flying', $pokemon->getTypes()[1]->value());
    }
}

