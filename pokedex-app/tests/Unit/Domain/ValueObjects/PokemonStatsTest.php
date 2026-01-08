<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\ValueObjects;

use App\Domain\ValueObjects\PokemonStats;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Pokemon Stats Value Object Tests
 * 
 * Tests the validation and behavior of PokemonStats value object.
 */
final class PokemonStatsTest extends TestCase
{
    public function test_creates_valid_pokemon_stats(): void
    {
        $stats = new PokemonStats(
            hp: 45,
            attack: 49,
            defense: 49,
            specialAttack: 65,
            specialDefense: 65,
            speed: 45
        );
        
        $this->assertSame(45, $stats->hp());
        $this->assertSame(49, $stats->attack());
        $this->assertSame(49, $stats->defense());
        $this->assertSame(65, $stats->specialAttack());
        $this->assertSame(65, $stats->specialDefense());
        $this->assertSame(45, $stats->speed());
        $this->assertSame(318, $stats->total());
    }

    public function test_calculates_total_correctly(): void
    {
        $stats = new PokemonStats(
            hp: 100,
            attack: 100,
            defense: 100,
            specialAttack: 100,
            specialDefense: 100,
            speed: 100
        );
        
        $this->assertSame(600, $stats->total());
    }

    public function test_rejects_negative_hp(): void
    {
        $this->expectException(InvalidArgumentException::class);
        
        new PokemonStats(
            hp: -1,
            attack: 50,
            defense: 50,
            specialAttack: 50,
            specialDefense: 50,
            speed: 50
        );
    }

    public function test_rejects_stat_above_255(): void
    {
        $this->expectException(InvalidArgumentException::class);
        
        new PokemonStats(
            hp: 256,
            attack: 50,
            defense: 50,
            specialAttack: 50,
            specialDefense: 50,
            speed: 50
        );
    }

    public function test_to_array_returns_correct_structure(): void
    {
        $stats = new PokemonStats(
            hp: 45,
            attack: 49,
            defense: 49,
            specialAttack: 65,
            specialDefense: 65,
            speed: 45
        );
        
        $expected = [
            'hp' => 45,
            'attack' => 49,
            'defense' => 49,
            'specialAttack' => 65,
            'specialDefense' => 65,
            'speed' => 45,
            'total' => 318,
        ];
        
        $this->assertSame($expected, $stats->toArray());
    }
}

