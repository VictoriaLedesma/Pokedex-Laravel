<?php

declare(strict_types=1);

namespace Tests\Unit\Application\UseCases;

use App\Application\UseCases\ListPokemonUseCase;
use App\Domain\Entities\Pokemon;
use App\Domain\Repositories\PokemonRepositoryInterface;
use App\Domain\ValueObjects\PokemonId;
use App\Domain\ValueObjects\PokemonName;
use App\Domain\ValueObjects\PokemonStats;
use App\Domain\ValueObjects\PokemonType;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * List Pokemon Use Case Tests
 * 
 * Tests the ListPokemonUseCase using mocked dependencies.
 * Demonstrates how to test use cases in isolation.
 */
final class ListPokemonUseCaseTest extends TestCase
{
    private PokemonRepositoryInterface&MockObject $repository;
    private ListPokemonUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->repository = $this->createMock(PokemonRepositoryInterface::class);
        $this->useCase = new ListPokemonUseCase($this->repository);
    }

    public function test_executes_successfully_with_pokemon(): void
    {
        $mockPokemon = [
            $this->createMockPokemon(1, 'bulbasaur'),
            $this->createMockPokemon(2, 'ivysaur'),
            $this->createMockPokemon(3, 'venusaur'),
        ];

        $this->repository
            ->expects($this->once())
            ->method('list')
            ->with(20, 0)
            ->willReturn($mockPokemon);

        $result = $this->useCase->execute(20, 0);

        $this->assertCount(3, $result);
        $this->assertSame(1, $result[0]->id);
        $this->assertSame('Bulbasaur', $result[0]->name);
        $this->assertSame(2, $result[1]->id);
        $this->assertSame('Ivysaur', $result[1]->name);
    }

    public function test_executes_successfully_with_empty_list(): void
    {
        $this->repository
            ->expects($this->once())
            ->method('list')
            ->with(20, 0)
            ->willReturn([]);

        $result = $this->useCase->execute(20, 0);

        $this->assertCount(0, $result);
    }

    public function test_passes_correct_parameters_to_repository(): void
    {
        $this->repository
            ->expects($this->once())
            ->method('list')
            ->with(50, 100)
            ->willReturn([]);

        $this->useCase->execute(50, 100);
    }

    private function createMockPokemon(int $id, string $name): Pokemon
    {
        return new Pokemon(
            id: new PokemonId($id),
            name: new PokemonName($name),
            imageUrl: 'https://example.com/image.png',
            types: [new PokemonType('grass')],
            stats: new PokemonStats(45, 49, 49, 65, 65, 45),
            height: 0.7,
            weight: 6.9,
        );
    }
}

