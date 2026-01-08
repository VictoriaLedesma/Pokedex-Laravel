<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\ValueObjects;

use App\Domain\ValueObjects\PokemonId;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Pokemon ID Value Object Tests
 * 
 * Tests the validation and behavior of PokemonId value object.
 */
final class PokemonIdTest extends TestCase
{
    public function test_creates_valid_pokemon_id(): void
    {
        $id = new PokemonId(25);
        
        $this->assertSame(25, $id->value());
        $this->assertSame('25', (string) $id);
    }

    public function test_rejects_zero_id(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Pokemon ID must be a positive integer');
        
        new PokemonId(0);
    }

    public function test_rejects_negative_id(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Pokemon ID must be a positive integer');
        
        new PokemonId(-1);
    }

    public function test_equals_method_works_correctly(): void
    {
        $id1 = new PokemonId(25);
        $id2 = new PokemonId(25);
        $id3 = new PokemonId(26);
        
        $this->assertTrue($id1->equals($id2));
        $this->assertFalse($id1->equals($id3));
    }
}

