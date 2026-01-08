# Quick Reference Guide

## 🚀 Quick Start

```bash
cd pokedex-app
composer install
cp env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan serve
```

Visit: `http://localhost:8000`

## 📂 Project Structure at a Glance

```
app/
├── Domain/              # Business rules (no dependencies)
│   ├── Entities/        # Pokemon entity
│   ├── ValueObjects/    # PokemonId, PokemonName, etc.
│   └── Repositories/    # Interfaces only
├── Application/         # Business workflows
│   ├── UseCases/        # ListPokemon, GetDetail, Search
│   └── DTOs/           # Data transfer objects
├── Infrastructure/      # External world
│   ├── Repositories/   # API implementation
│   └── Services/       # HTTP client, Mapper
└── Http/               # Web interface
    ├── Controllers/    # Thin controllers
    └── Requests/       # Validation
```

## 🎯 Common Tasks

### Add New Use Case

1. Create use case in `app/Application/UseCases/`:
```php
final readonly class YourUseCase
{
    public function __construct(
        private PokemonRepositoryInterface $repo
    ) {}

    public function execute(): mixed
    {
        // Business logic here
    }
}
```

2. Use in controller:
```php
public function __construct(
    private YourUseCase $useCase
) {}
```

### Add New Value Object

```php
// app/Domain/ValueObjects/YourValueObject.php
final readonly class YourValueObject
{
    public function __construct(private string $value)
    {
        $this->validate($value);
    }

    private function validate(string $value): void
    {
        // Validation logic
    }

    public function value(): string
    {
        return $this->value;
    }
}
```

### Add New Repository Method

1. Add to interface:
```php
// app/Domain/Repositories/PokemonRepositoryInterface.php
public function yourMethod(): mixed;
```

2. Implement:
```php
// app/Infrastructure/Repositories/PokeApiPokemonRepository.php
public function yourMethod(): mixed
{
    // Implementation
}
```

### Add Service Binding

```php
// app/Providers/AppServiceProvider.php
public function register(): void
{
    $this->app->bind(
        YourInterface::class,
        YourImplementation::class
    );
}
```

## 🧪 Testing Commands

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=PokemonNameTest

# Run with coverage
php artisan test --coverage

# Run only unit tests
./vendor/bin/phpunit --testsuite Unit

# Run only feature tests
./vendor/bin/phpunit --testsuite Feature
```

## 📝 Code Standards

### File Template

```php
<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects;

/**
 * Class description
 * 
 * Longer explanation if needed.
 */
final readonly class ClassName
{
    public function __construct(
        private Type $property
    ) {
        $this->validate();
    }

    private function validate(): void
    {
        // Validation logic
    }

    public function value(): Type
    {
        return $this->property;
    }
}
```

### Naming Conventions

| Type | Convention | Example |
|------|-----------|---------|
| Class | PascalCase | `PokemonService` |
| Method | camelCase | `findById()` |
| Variable | camelCase | `$pokemonList` |
| Constant | UPPER_SNAKE | `MAX_RETRY` |
| Interface | ...Interface | `RepositoryInterface` |
| Use Case | {Verb}{Noun}UseCase | `ListPokemonUseCase` |
| DTO | ...DTO | `PokemonDTO` |
| Value Object | Descriptive | `PokemonId` |

## 🔍 Debugging

### Clear Caches
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

### View Logs
```bash
tail -f storage/logs/laravel.log
```

### Debug Mode
```env
# .env
APP_DEBUG=true
LOG_LEVEL=debug
```

## 🗺️ Routes

| URL | Method | Controller | Action |
|-----|--------|-----------|--------|
| `/` | GET | PokemonController | index |
| `/pokemon` | GET | PokemonController | index |
| `/pokemon/search` | GET | PokemonController | search |
| `/pokemon/{id}` | GET | PokemonController | show |

## 🎨 View Locations

```
resources/views/
├── layouts/
│   └── app.blade.php      # Main layout
└── pokemon/
    ├── index.blade.php    # List view
    ├── show.blade.php     # Detail view
    └── search.blade.php   # Search results
```

## ⚙️ Configuration

### Environment Variables
```env
# PokéAPI
POKEAPI_BASE_URL=https://pokeapi.co/api/v2
POKEAPI_TIMEOUT=30
POKEAPI_CACHE_TTL=3600

# Cache
CACHE_STORE=file

# App
APP_DEBUG=false
APP_ENV=production
```

### Config Files
- `config/app.php` - App settings
- `config/cache.php` - Cache settings
- `config/services.php` - External services (PokéAPI)

## 🔧 Artisan Commands

```bash
# Development
php artisan serve              # Start dev server
php artisan tinker            # Interactive shell

# Cache
php artisan cache:clear       # Clear cache
php artisan config:cache      # Cache config (production)
php artisan route:cache       # Cache routes (production)
php artisan view:cache        # Cache views (production)

# Utilities
php artisan list              # List all commands
php artisan help {command}    # Help for command
```

## 📊 Performance Tips

### Development
```bash
# Clear all caches for hot reload
php artisan optimize:clear
```

### Production
```bash
# Optimize everything
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 🐛 Common Issues & Solutions

### "Class not found"
```bash
composer dump-autoload
```

### Port already in use
```bash
php artisan serve --port=8080
```

### Permission denied (Linux/Mac)
```bash
chmod -R 775 storage bootstrap/cache
```

### Cache issues
```bash
php artisan optimize:clear
```

## 📚 Documentation Files

- `README.md` - Project overview
- `INSTALL.md` - Installation guide
- `ARCHITECTURE.md` - Architecture deep dive
- `TECHNICAL_SUMMARY.md` - Technical decisions
- `CONTRIBUTING.md` - How to contribute
- `CHANGELOG.md` - Version history
- `QUICK_REFERENCE.md` - This file

## 🔗 Useful Links

- [Laravel Documentation](https://laravel.com/docs)
- [PokéAPI Documentation](https://pokeapi.co/docs/v2)
- [PSR-12 Standard](https://www.php-fig.org/psr/psr-12/)
- [Clean Architecture](https://blog.cleancoder.com/uncle-bob/2012/08/13/the-clean-architecture.html)
- [SOLID Principles](https://en.wikipedia.org/wiki/SOLID)

## 💡 Pro Tips

1. **Always validate at the edge**: Use FormRequests for HTTP, Value Objects for domain
2. **Keep controllers thin**: Delegate to use cases immediately
3. **Test value objects first**: They're the foundation
4. **Mock at boundaries**: Mock repositories, not use cases
5. **Document decisions**: Update ARCHITECTURE.md for big changes
6. **Run tests before commit**: `php artisan test`
7. **Use readonly**: Makes objects immutable by default
8. **Type everything**: Catch errors at compile time

## 🎓 Learning Path

1. Read `README.md` - Understand what it does
2. Read `ARCHITECTURE.md` - Understand how it works
3. Explore `app/Domain/` - See pure business logic
4. Read tests in `tests/Unit/` - See how to test
5. Modify a use case - Practice making changes
6. Add a feature - Build something new

## 🚨 Remember

- ✅ Business logic in Domain/Application
- ✅ Controllers are thin
- ✅ Always inject dependencies
- ✅ Write tests for new code
- ✅ Keep layers independent
- ✅ Use value objects for domain concepts
- ❌ Never `new` in controllers
- ❌ No business logic in controllers
- ❌ Don't skip tests
- ❌ Don't couple to frameworks in domain

---

**Keep this file bookmarked for quick reference!** 📌

