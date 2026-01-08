<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domain\Repositories\PokemonRepositoryInterface;
use App\Infrastructure\Repositories\PokeApiPokemonRepository;
use App\Infrastructure\Services\PokeApiClient;
use App\Infrastructure\Services\PokemonMapper;
use Illuminate\Support\ServiceProvider;

/**
 * Application Service Provider
 * 
 * Registers all dependency injection bindings.
 * This is where we bind interfaces to implementations,
 * following the Dependency Inversion Principle.
 */
final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register PokéAPI Client as singleton
        $this->app->singleton(PokeApiClient::class, function ($app) {
            return new PokeApiClient();
        });

        // Register Pokemon Mapper as singleton
        $this->app->singleton(PokemonMapper::class, function ($app) {
            return new PokemonMapper();
        });

        // Bind Repository Interface to Implementation
        // This is the key to Dependency Inversion Principle
        $this->app->bind(
            PokemonRepositoryInterface::class,
            PokeApiPokemonRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

