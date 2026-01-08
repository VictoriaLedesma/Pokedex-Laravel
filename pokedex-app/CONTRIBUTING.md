# Contributing to Pokédex

Thank you for considering contributing to this project! This document provides guidelines for contributing.

## Code of Conduct

- Be respectful and professional
- Welcome newcomers and beginners
- Focus on constructive feedback
- Maintain a positive environment

## Development Setup

1. Fork the repository
2. Clone your fork
3. Follow the [INSTALL.md](INSTALL.md) guide
4. Create a new branch for your feature

```bash
git checkout -b feature/your-feature-name
```

## Coding Standards

### PHP Standards

- **PSR-12** code style
- **Strict types** always: `declare(strict_types=1);`
- **Type hints** everywhere (parameters and return types)
- **Final classes** by default
- **Readonly properties** where possible

### Architecture Principles

Follow these principles strictly:

1. **Single Responsibility**: One class, one reason to change
2. **Dependency Inversion**: Depend on abstractions, not implementations
3. **No business logic** in controllers or models
4. **Use cases** for all business operations
5. **Value objects** for domain concepts
6. **DTOs** for data transfer between layers

### Naming Conventions

```php
// Classes: PascalCase
class PokemonService { }

// Methods: camelCase
public function findById() { }

// Constants: UPPER_SNAKE_CASE
private const MAX_RETRY_ATTEMPTS = 3;

// Variables: camelCase
$pokemonList = [];

// Interfaces: end with "Interface"
interface PokemonRepositoryInterface { }

// Use Cases: {Verb}{Noun}UseCase
class GetPokemonDetailUseCase { }

// DTOs: end with "DTO"
class PokemonDTO { }

// Value Objects: descriptive names
class PokemonId { }
```

### File Organization

Place files in the correct layer:

```
app/
├── Domain/           # Business logic, no dependencies
│   ├── Entities/
│   ├── ValueObjects/
│   ├── Repositories/ # Interfaces only
│   └── Services/     # Interfaces only
├── Application/      # Use cases
│   ├── UseCases/
│   └── DTOs/
├── Infrastructure/   # External integrations
│   ├── Repositories/ # Implementations
│   └── Services/     # Implementations
└── Http/            # Web layer
    ├── Controllers/
    └── Requests/
```

## Testing Requirements

All contributions MUST include tests:

### Unit Tests Required

- New value objects
- New use cases
- New domain services
- Business logic

### Integration Tests Required

- Repository implementations
- API integrations
- Mappers

### Example Unit Test

```php
final class PokemonNameTest extends TestCase
{
    public function test_creates_valid_name(): void
    {
        $name = new PokemonName('pikachu');
        
        $this->assertSame('pikachu', $name->value());
    }

    public function test_rejects_empty_name(): void
    {
        $this->expectException(InvalidArgumentException::class);
        
        new PokemonName('');
    }
}
```

### Running Tests

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=PokemonNameTest

# Run with coverage
php artisan test --coverage
```

## Pull Request Process

1. **Create a feature branch**
```bash
git checkout -b feature/add-favorite-system
```

2. **Make your changes**
   - Follow coding standards
   - Write tests
   - Update documentation

3. **Commit your changes**
```bash
git add .
git commit -m "feat: add favorite Pokemon system"
```

### Commit Message Format

Use conventional commits:

```
type(scope): subject

body (optional)

footer (optional)
```

**Types:**
- `feat`: New feature
- `fix`: Bug fix
- `docs`: Documentation only
- `style`: Code style changes
- `refactor`: Code refactoring
- `test`: Adding tests
- `chore`: Maintenance tasks

**Examples:**
```
feat(pokemon): add favorite system
fix(api): handle timeout errors gracefully
docs(readme): update installation instructions
test(value-objects): add PokemonType tests
```

4. **Push to your fork**
```bash
git push origin feature/add-favorite-system
```

5. **Create Pull Request**
   - Clear description of changes
   - Link to any related issues
   - Include screenshots for UI changes
   - Ensure all tests pass

## What to Contribute

### Good First Issues

- Adding more unit tests
- Improving documentation
- Fixing typos
- Adding code comments
- UI improvements

### Advanced Contributions

- New features (discuss first!)
- Performance optimizations
- Refactoring
- New integrations

### Feature Requests

Before implementing a major feature:

1. Open an issue to discuss
2. Wait for approval
3. Create a design document
4. Get feedback on approach
5. Start implementation

## Code Review Process

All submissions require review:

1. Automated tests must pass
2. Code must follow standards
3. Must include tests
4. Documentation must be updated
5. At least one approval required

### Review Checklist

Reviewers will check:

- [ ] Follows SOLID principles
- [ ] No business logic in controllers
- [ ] Uses dependency injection
- [ ] Includes tests
- [ ] Documentation updated
- [ ] No breaking changes (or documented)
- [ ] Performance considered
- [ ] Security considered

## Documentation

Update documentation for:

- New features
- Changed behavior
- New configuration options
- Breaking changes

Files to update:
- `README.md` - Overview and features
- `ARCHITECTURE.md` - Architecture decisions
- `INSTALL.md` - Installation steps
- Code comments - Complex logic

## Questions?

If you have questions:

1. Check existing documentation
2. Search closed issues
3. Open a new issue with "Question:" prefix

## Recognition

Contributors will be recognized in:
- README.md (Contributors section)
- Release notes
- Project documentation

Thank you for contributing! 🎉

