# 🔥 Pokédex Application - Laravel with Clean Architecture

> A modern, scalable Pokédex application built with Laravel, following Clean Architecture principles and SOLID design patterns.

[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php)](https://php.net)
[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=flat&logo=laravel)](https://laravel.com)
[![Clean Architecture](https://img.shields.io/badge/Architecture-Clean-blue)](https://blog.cleancoder.com/uncle-bob/2012/08/13/the-clean-architecture.html)
[![SOLID](https://img.shields.io/badge/Principles-SOLID-green)](https://en.wikipedia.org/wiki/SOLID)

## 📋 Table of Contents

- [Overview](#-overview)
- [Features](#-features)
- [Architecture](#-architecture)
- [Project Structure](#-project-structure)
- [Installation](#-installation)
- [Usage](#-usage)
- [Testing](#-testing)
- [Technical Decisions](#-technical-decisions)
- [SOLID Principles Applied](#-solid-principles-applied)
- [API Documentation](#-api-documentation)

## 🎯 Overview

This project is a technical challenge solution that demonstrates professional software engineering practices in PHP/Laravel. It consumes the [PokéAPI](https://pokeapi.co/) to display Pokémon information through a clean, modern web interface.

**Key Highlights:**
- ✅ Clean Architecture with clear separation of concerns
- ✅ SOLID principles applied throughout
- ✅ Fully typed with PHP 8.2+ features (readonly properties, strict types)
- ✅ Comprehensive unit and integration tests
- ✅ Zero business logic in Controllers or Eloquent Models
- ✅ Dependency Injection everywhere
- ✅ PSR-12 compliant code style

## ✨ Features

### Required Features (100% Complete)

✅ **Pokémon Listing**
- Display 20 Pokémon per page with pagination
- Shows image, name, and Pokédex number
- Clickable cards leading to detail view

✅ **Pokémon Detail**
- Complete Pokémon information display
- Image, name, and Pokédex number
- Types with color-coded badges
- Battle statistics with visual bars
- Height and weight in metric units

✅ **Search Functionality**
- Search by Pokémon name or number
- Real-time validation
- Dedicated search results page

### Additional Features

🚀 **Performance Optimization**
- Smart caching of API responses (1 hour TTL)
- Automatic retry logic for failed requests
- Optimized image loading with lazy loading

🎨 **Modern UI/UX**
- Responsive design (mobile, tablet, desktop)
- Smooth animations and transitions
- Type-specific color schemes
- Gradient backgrounds
- Interactive hover effects

🛡️ **Error Handling**
- Graceful degradation on API failures
- User-friendly error messages
- Comprehensive logging

## 🏛️ Architecture

This project implements **Clean Architecture** (Hexagonal Architecture) with DDD-inspired patterns:

```
┌─────────────────────────────────────────┐
│           Presentation Layer            │
│         (HTTP Controllers/Views)        │
└─────────────────┬───────────────────────┘
                  │
┌─────────────────▼───────────────────────┐
│         Application Layer               │
│       (Use Cases, DTOs)                 │
└─────────────────┬───────────────────────┘
                  │
┌─────────────────▼───────────────────────┐
│            Domain Layer                 │
│  (Entities, Value Objects, Interfaces)  │
└─────────────────┬───────────────────────┘
                  │
┌─────────────────▼───────────────────────┐
│        Infrastructure Layer             │
│   (API Clients, Repositories, Cache)    │
└─────────────────────────────────────────┘
```

### Layer Responsibilities

**Domain Layer** (`app/Domain/`)
- Pure business logic
- No framework dependencies
- Entities, Value Objects, Repository Interfaces
- The heart of the application

**Application Layer** (`app/Application/`)
- Use Cases (business orchestration)
- DTOs for data transfer
- Coordinates domain objects

**Infrastructure Layer** (`app/Infrastructure/`)
- External service implementations
- API clients (PokéAPI)
- Repository implementations
- Persistence logic

**Presentation Layer** (`app/Http/`)
- Controllers (thin, no business logic)
- Form Requests (validation)
- Views (Blade templates)

## 📁 Project Structure

```
pokedex-app/
├── app/
│   ├── Domain/                    # Business logic core
│   │   ├── Entities/
│   │   │   └── Pokemon.php       # Pokemon entity
│   │   ├── ValueObjects/
│   │   │   ├── PokemonId.php     # Self-validating ID
│   │   │   ├── PokemonName.php   # Self-validating name
│   │   │   ├── PokemonType.php   # Type with colors
│   │   │   └── PokemonStats.php  # Battle stats
│   │   ├── Repositories/
│   │   │   └── PokemonRepositoryInterface.php
│   │   └── Services/             # Domain service interfaces
│   │
│   ├── Application/               # Use cases
│   │   ├── UseCases/
│   │   │   ├── ListPokemonUseCase.php
│   │   │   ├── GetPokemonDetailUseCase.php
│   │   │   └── SearchPokemonUseCase.php
│   │   └── DTOs/
│   │       ├── PokemonDTO.php
│   │       └── PokemonListItemDTO.php
│   │
│   ├── Infrastructure/            # External integrations
│   │   ├── Repositories/
│   │   │   └── PokeApiPokemonRepository.php
│   │   └── Services/
│   │       ├── PokeApiClient.php      # HTTP client
│   │       └── PokemonMapper.php      # API → Domain mapper
│   │
│   ├── Http/                      # Web layer
│   │   ├── Controllers/
│   │   │   └── PokemonController.php  # Thin controller
│   │   └── Requests/
│   │       └── SearchPokemonRequest.php
│   │
│   └── Providers/
│       └── AppServiceProvider.php     # DI bindings
│
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php          # Main layout
│       └── pokemon/
│           ├── index.blade.php        # List view
│           ├── show.blade.php         # Detail view
│           └── search.blade.php       # Search results
│
├── tests/
│   ├── Unit/
│   │   ├── Domain/ValueObjects/       # Value object tests
│   │   ├── Application/UseCases/      # Use case tests (with mocks)
│   │   └── Infrastructure/Services/   # Mapper tests
│   └── Feature/                       # Integration tests
│
├── routes/
│   └── web.php                        # Route definitions
│
├── config/
│   └── services.php                   # PokéAPI configuration
│
├── composer.json                      # Dependencies
├── phpunit.xml                        # Test configuration
└── README.md                          # This file
```

## 🚀 Installation

### Prerequisites

- PHP 8.2 or higher
- Composer
- SQLite (or any database supported by Laravel)

### Step-by-Step Setup

1. **Clone the repository**
```bash
cd pokedex-app
```

2. **Install dependencies**
```bash
composer install
```

3. **Configure environment**
```bash
cp env.example .env
php artisan key:generate
```

4. **Configure the database**

For SQLite (default):
```bash
touch database/database.sqlite
```

Or update `.env` for other databases:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pokedex
DB_USERNAME=root
DB_PASSWORD=
```

5. **Run migrations** (if needed)
```bash
php artisan migrate
```

6. **Start the development server**
```bash
php artisan serve
```

7. **Access the application**
```
http://localhost:8000
```

## 💻 Usage

### Main Pages

- **Home/List**: `http://localhost:8000/`
  - Browse all Pokémon with pagination
  - Click on any card to see details

- **Detail**: `http://localhost:8000/pokemon/{id-or-name}`
  - View complete Pokémon information
  - Examples: `/pokemon/25` or `/pokemon/pikachu`

- **Search**: Use the search bar in the navigation
  - Search by name: "pikachu"
  - Search by number: "25"

### API Configuration

PokéAPI settings are in `.env`:
```env
POKEAPI_BASE_URL=https://pokeapi.co/api/v2
POKEAPI_TIMEOUT=30
POKEAPI_CACHE_TTL=3600  # Cache for 1 hour
```

## 🧪 Testing

### Run All Tests
```bash
php artisan test
# or
./vendor/bin/phpunit
```

### Run Specific Test Suites
```bash
# Unit tests only
./vendor/bin/phpunit --testsuite Unit

# Feature tests only
./vendor/bin/phpunit --testsuite Feature
```

### Test Coverage
```bash
./vendor/bin/phpunit --coverage-html coverage
```

### What's Tested

✅ **Value Objects**
- Validation logic
- Immutability
- Edge cases

✅ **Use Cases**
- Business logic
- Mocked dependencies
- Error handling

✅ **Infrastructure**
- API response mapping
- Data transformation
- Type conversions

## 🤔 Technical Decisions

### Why Clean Architecture?

1. **Testability**: Easy to test business logic in isolation
2. **Maintainability**: Changes in one layer don't affect others
3. **Scalability**: Easy to add new features without breaking existing code
4. **Framework Independence**: Business logic doesn't depend on Laravel

### Why Value Objects?

1. **Type Safety**: Prevent invalid data at compile time
2. **Self-Validation**: Rules are encapsulated
3. **Immutability**: Prevents accidental mutations
4. **Domain Expression**: Code reads like business language

### Why No Eloquent Models for Business Logic?

- Eloquent is infrastructure, not domain
- Keeps domain pure and framework-independent
- Easier to switch data sources
- Better separation of concerns

### Why DTOs?

- Decouple layers
- Clear data contracts
- Easy serialization
- Type-safe data transfer

### Why Use Cases?

- Single Responsibility: one use case = one action
- Reusable across different entry points (web, CLI, API)
- Testable in isolation
- Clear business intent

## 🎯 SOLID Principles Applied

### **S**ingle Responsibility Principle
- Each class has ONE reason to change
- `PokemonController`: HTTP handling only
- `ListPokemonUseCase`: List Pokémon logic only
- `PokeApiClient`: HTTP communication only

### **O**pen/Closed Principle
- Open for extension, closed for modification
- Want a different data source? Implement `PokemonRepositoryInterface`
- No need to change existing code

### **L**iskov Substitution Principle
- Any implementation of `PokemonRepositoryInterface` can replace another
- Controllers depend on interfaces, not concrete classes

### **I**nterface Segregation Principle
- Small, focused interfaces
- `PokemonRepositoryInterface` has only repository methods
- No "god" interfaces

### **D**ependency Inversion Principle
- High-level modules don't depend on low-level modules
- Both depend on abstractions (interfaces)
- Dependencies are injected via constructor

Example:
```php
// ❌ Bad: Direct dependency
class PokemonController {
    public function index() {
        $repo = new PokeApiPokemonRepository(); // Hard-coded!
    }
}

// ✅ Good: Depend on abstraction
class PokemonController {
    public function __construct(
        private PokemonRepositoryInterface $repository // Injected!
    ) {}
}
```

## 📚 API Documentation

### PokéAPI Integration

This app consumes the following PokéAPI endpoints:

- `GET /pokemon?limit={limit}&offset={offset}` - List Pokémon
- `GET /pokemon/{id}` - Get Pokémon by ID
- `GET /pokemon/{name}` - Get Pokémon by name

### Caching Strategy

- All API responses are cached for 1 hour
- Cache keys:
  - `pokemon:id:{id}`
  - `pokemon:name:{name}`
  - `pokemon:list:{limit}:{offset}`

### Error Handling

- **404**: Pokémon not found → Returns null, shows user-friendly message
- **Timeout**: Retries 3 times with 100ms delay
- **500+**: Logs error, shows generic error message

## 🎨 UI/UX Features

- **Responsive Design**: Works on all screen sizes
- **Type Colors**: Each type has its distinct color
- **Loading States**: Lazy loading for images
- **Smooth Animations**: Card hovers, transitions
- **Accessibility**: Semantic HTML, proper contrast ratios

## 📝 Code Style

- **PSR-12** compliant
- **Strict typing** everywhere (`declare(strict_types=1)`)
- **Readonly properties** where possible
- **Final classes** by default (composition over inheritance)
- **Meaningful names** that express intent
- **No abbreviations** unless universally understood

## 🔮 Future Enhancements

Potential additions:
- [ ] Favorite Pokémon system with local storage
- [ ] Advanced filters (by type, generation, stats)
- [ ] Pokémon comparison tool
- [ ] Evolution chain visualization
- [ ] Move details
- [ ] Ability information
- [ ] Multi-language support
- [ ] Dark mode

## 📄 License

MIT License - feel free to use this project for learning purposes.

## 🙏 Acknowledgments

- [PokéAPI](https://pokeapi.co/) for the amazing free API
- [Laravel](https://laravel.com/) for the excellent framework
- [Tailwind CSS](https://tailwindcss.com/) for rapid styling
- Uncle Bob Martin for Clean Architecture principles

## 👨‍💻 Author

Built with ❤️ as a technical challenge demonstration, showcasing:
- Professional software architecture
- SOLID principles
- Clean code practices
- Comprehensive testing
- Modern PHP features

---

**🚀 Happy Coding!**

