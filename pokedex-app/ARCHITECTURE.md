# Architecture Documentation

## Overview

This document explains the architectural decisions and patterns used in this Pokédex application.

## Clean Architecture Implementation

### Layer Separation

```
Presentation (HTTP) → Application (Use Cases) → Domain (Business Logic) → Infrastructure (External Services)
```

**Key Principle**: Dependencies point inward. Domain has no dependencies. Infrastructure depends on Domain.

### Dependency Flow

```php
// ✅ CORRECT: Infrastructure depends on Domain
class PokeApiPokemonRepository implements PokemonRepositoryInterface
{
    // Implementation depends on interface defined in Domain
}

// ✅ CORRECT: Application depends on Domain
class ListPokemonUseCase
{
    public function __construct(
        private PokemonRepositoryInterface $repository // Domain interface
    ) {}
}

// ✅ CORRECT: HTTP depends on Application
class PokemonController
{
    public function __construct(
        private ListPokemonUseCase $useCase // Application layer
    ) {}
}
```

## SOLID Principles in Practice

### Single Responsibility Principle (SRP)

Each class has ONE reason to change:

**Example: Pokemon Repository**
```php
// ❌ BAD: Multiple responsibilities
class PokemonService
{
    public function fetchFromApi() { }
    public function validatePokemon() { }
    public function cachePokemon() { }
    public function renderView() { }
}

// ✅ GOOD: Single responsibility per class
class PokeApiClient { } // Only HTTP communication
class PokemonMapper { } // Only data mapping
class PokemonRepository { } // Only data access orchestration
```

### Open/Closed Principle (OCP)

**Open for extension, closed for modification**

```php
// Domain defines the contract
interface PokemonRepositoryInterface
{
    public function findById(PokemonId $id): ?Pokemon;
}

// Easy to add new implementations WITHOUT modifying existing code
class PokeApiPokemonRepository implements PokemonRepositoryInterface { }
class DatabasePokemonRepository implements PokemonRepositoryInterface { }
class FilePokemonRepository implements PokemonRepositoryInterface { }
```

Want to add caching? Create a decorator:
```php
class CachedPokemonRepository implements PokemonRepositoryInterface
{
    public function __construct(
        private PokemonRepositoryInterface $decorated,
        private CacheInterface $cache
    ) {}
    
    public function findById(PokemonId $id): ?Pokemon
    {
        return $this->cache->remember(
            "pokemon:{$id}",
            fn() => $this->decorated->findById($id)
        );
    }
}
```

### Liskov Substitution Principle (LSP)

Any implementation can substitute another:

```php
// These are interchangeable:
$repo = new PokeApiPokemonRepository();
$repo = new DatabasePokemonRepository();
$repo = new CachedPokemonRepository(new PokeApiPokemonRepository());

// Use case doesn't care which implementation it gets
$useCase = new ListPokemonUseCase($repo);
```

### Interface Segregation Principle (ISP)

Small, focused interfaces:

```php
// ✅ GOOD: Specific interfaces
interface PokemonRepositoryInterface { }
interface PokemonCacheInterface { }
interface PokemonValidatorInterface { }

// ❌ BAD: God interface
interface PokemonServiceInterface
{
    public function find();
    public function cache();
    public function validate();
    public function render();
    public function export();
    // ...20 more methods
}
```

### Dependency Inversion Principle (DIP)

**Depend on abstractions, not concretions**

```php
// ❌ BAD: High-level module depends on low-level module
class ListPokemonUseCase
{
    private PokeApiPokemonRepository $repo; // Concrete class
    
    public function __construct()
    {
        $this->repo = new PokeApiPokemonRepository(); // new keyword!
    }
}

// ✅ GOOD: Both depend on abstraction
class ListPokemonUseCase
{
    public function __construct(
        private PokemonRepositoryInterface $repo // Interface
    ) {}
}

// Binding happens in Service Provider
$this->app->bind(
    PokemonRepositoryInterface::class,
    PokeApiPokemonRepository::class
);
```

## Value Objects

### Why Value Objects?

1. **Type Safety**: Impossible to pass invalid data
2. **Self-Validating**: Validation happens in constructor
3. **Immutable**: Can't be changed after creation
4. **Explicit**: `PokemonId` is more explicit than `int`

### Example

```php
// ❌ BAD: Primitive obsession
function getPokemon(int $id): Pokemon
{
    if ($id < 1) {
        throw new Exception('Invalid ID');
    }
    // ...
}

// ✅ GOOD: Value object
function getPokemon(PokemonId $id): Pokemon
{
    // ID is already valid!
    // No need to validate
}

// Validation happens at construction
$id = new PokemonId(-1); // Throws exception immediately
```

## Use Cases

### What is a Use Case?

A use case represents **one specific business action**.

**Naming Convention**: `{Verb}{Noun}UseCase`
- `ListPokemonUseCase`
- `GetPokemonDetailUseCase`
- `SearchPokemonUseCase`

### Why Use Cases?

1. **Testable**: Easy to test with mocked dependencies
2. **Reusable**: Can be called from web, CLI, API, etc.
3. **Single Responsibility**: One action per use case
4. **Clear Intent**: Name describes exactly what it does

### Anatomy of a Use Case

```php
final readonly class ListPokemonUseCase
{
    // 1. Dependencies injected via constructor
    public function __construct(
        private PokemonRepositoryInterface $repository
    ) {}

    // 2. Single public method: execute
    public function execute(int $limit, int $offset): array
    {
        // 3. Orchestrate domain objects
        $pokemon = $this->repository->list($limit, $offset);

        // 4. Return DTOs (not entities!)
        return array_map(
            fn($p) => new PokemonListItemDTO(...),
            $pokemon
        );
    }
}
```

## DTOs (Data Transfer Objects)

### Purpose

Transfer data between layers WITHOUT exposing domain entities.

```php
// ❌ BAD: Exposing domain entity
class PokemonController
{
    public function show($id): View
    {
        $pokemon = $this->useCase->execute($id); // Returns Pokemon entity
        return view('pokemon.show', ['pokemon' => $pokemon]);
    }
}

// ✅ GOOD: Using DTO
class PokemonController
{
    public function show($id): View
    {
        $dto = $this->useCase->execute($id); // Returns PokemonDTO
        return view('pokemon.show', ['pokemon' => $dto]);
    }
}
```

### Benefits

1. **Decoupling**: Views don't depend on domain entities
2. **Versioning**: Easy to have multiple DTO versions
3. **Serialization**: DTOs are designed for serialization
4. **Simplicity**: Only contains data needed for that specific use case

## Repository Pattern

### Interface in Domain, Implementation in Infrastructure

```php
// Domain/Repositories/PokemonRepositoryInterface.php
namespace App\Domain\Repositories;

interface PokemonRepositoryInterface
{
    public function findById(PokemonId $id): ?Pokemon;
}

// Infrastructure/Repositories/PokeApiPokemonRepository.php
namespace App\Infrastructure\Repositories;

class PokeApiPokemonRepository implements PokemonRepositoryInterface
{
    public function __construct(
        private PokeApiClient $client,
        private PokemonMapper $mapper
    ) {}

    public function findById(PokemonId $id): ?Pokemon
    {
        $data = $this->client->getPokemonById($id->value());
        return $this->mapper->mapToPokemon($data);
    }
}
```

### Why This Pattern?

1. **Testability**: Easy to create fake implementations for tests
2. **Flexibility**: Swap data sources without changing business logic
3. **Separation**: Domain doesn't know about API, database, etc.

## Dependency Injection

### Service Container Setup

```php
// app/Providers/AppServiceProvider.php
public function register(): void
{
    // Singleton: One instance shared
    $this->app->singleton(PokeApiClient::class);
    $this->app->singleton(PokemonMapper::class);

    // Bind interface to implementation
    $this->app->bind(
        PokemonRepositoryInterface::class,
        PokeApiPokemonRepository::class
    );
}
```

### Automatic Resolution

Laravel automatically resolves dependencies:

```php
// Controller
public function __construct(
    private ListPokemonUseCase $useCase // Auto-resolved
) {}

// Use Case
public function __construct(
    private PokemonRepositoryInterface $repo // Auto-resolved
) {}

// Repository
public function __construct(
    private PokeApiClient $client, // Auto-resolved
    private PokemonMapper $mapper  // Auto-resolved
) {}
```

## Testing Strategy

### Unit Tests

Test individual components in isolation:

```php
final class ListPokemonUseCaseTest extends TestCase
{
    public function test_executes_successfully(): void
    {
        // Mock dependencies
        $repo = $this->createMock(PokemonRepositoryInterface::class);
        $repo->expects($this->once())
             ->method('list')
             ->willReturn([/* mock data */]);

        // Test use case in isolation
        $useCase = new ListPokemonUseCase($repo);
        $result = $useCase->execute(20, 0);

        $this->assertCount(3, $result);
    }
}
```

### Integration Tests

Test components working together:

```php
final class PokemonMapperTest extends TestCase
{
    public function test_maps_api_response_to_entity(): void
    {
        $mapper = new PokemonMapper();
        $apiData = [/* real API structure */];

        $pokemon = $mapper->mapToPokemon($apiData);

        $this->assertInstanceOf(Pokemon::class, $pokemon);
        $this->assertSame(25, $pokemon->getId()->value());
    }
}
```

## Performance Considerations

### Caching Strategy

```php
class PokeApiClient
{
    public function getPokemonById(int $id): ?array
    {
        return Cache::remember(
            "pokemon:id:{$id}",
            $this->cacheTtl,
            fn() => $this->makeRequest("/pokemon/{$id}")
        );
    }
}
```

### Retry Logic

```php
$response = Http::timeout($this->timeout)
    ->retry(3, 100) // Retry 3 times, wait 100ms
    ->get($url);
```

## Error Handling

### Graceful Degradation

```php
try {
    $pokemon = $this->repository->findById($id);
} catch (\Exception $e) {
    Log::error('Failed to fetch Pokemon', [
        'id' => $id,
        'error' => $e->getMessage()
    ]);
    return null; // Graceful failure
}
```

## Future Enhancements

### Adding Features Without Breaking Existing Code

**Example: Add caching layer**

```php
// 1. Create decorator
class CachedPokemonRepository implements PokemonRepositoryInterface
{
    public function __construct(
        private PokemonRepositoryInterface $decorated
    ) {}
    
    // Implement caching logic
}

// 2. Update binding
$this->app->bind(
    PokemonRepositoryInterface::class,
    function ($app) {
        return new CachedPokemonRepository(
            new PokeApiPokemonRepository(...)
        );
    }
);
```

**No other code needs to change!** 🎉

## Conclusion

This architecture provides:

✅ **Testability**: Every component can be tested in isolation
✅ **Maintainability**: Changes are localized
✅ **Scalability**: Easy to add new features
✅ **Flexibility**: Easy to swap implementations
✅ **Clarity**: Clear responsibilities and boundaries

**Trade-offs**:
- More files/classes (but each is simple)
- Steeper learning curve (but better long-term)
- Slightly more boilerplate (but much more maintainable)

**When to use this architecture**:
- Projects expected to grow
- Teams with multiple developers
- Long-term maintenance expected
- Business logic complexity

**When NOT to use**:
- Quick prototypes/MVPs
- Throwaway code
- Very simple CRUD apps
- Learning/tutorial projects

This Pokédex app demonstrates that even "simple" apps benefit from good architecture! 🚀

