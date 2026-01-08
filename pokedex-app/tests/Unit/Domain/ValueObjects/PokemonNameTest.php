<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\ValueObjects;

use App\Domain\ValueObjects\PokemonName;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Pokemon Name Value Object Tests
 * 
 * Tests the validation and behavior of PokemonName value object.
 */
final class PokemonNameTest extends TestCase
{
    public function test_creates_valid_pokemon_name(): void
    {
        $name = new PokemonName('pikachu');
        
        $this->assertSame('pikachu', $name->value());
        $this->assertSame('Pikachu', $name->formatted());
    }

    public function test_normalizes_name_to_lowercase(): void
    {
        $name = new PokemonName('PIKACHU');
        
        $this->assertSame('pikachu', $name->value());
    }

    public function test_trims_whitespace(): void
    {
        $name = new PokemonName('  pikachu  ');
        
        $this->assertSame('pikachu', $name->value());
    }

    public function test_rejects_empty_name(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Pokemon name cannot be empty');
        
        new PokemonName('');
    }

    public function test_rejects_whitespace_only_name(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Pokemon name cannot be empty');
        
        new PokemonName('   ');
    }

    public function test_rejects_too_long_name(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Pokemon name is too long');
        
        new PokemonName(str_repeat('a', 101));
    }

    public function test_equals_method_works_correctly(): void
    {
        $name1 = new PokemonName('pikachu');
        $name2 = new PokemonName('PIKACHU');
        $name3 = new PokemonName('charizard');
        
        $this->assertTrue($name1->equals($name2));
        $this->assertFalse($name1->equals($name3));
    }
}

