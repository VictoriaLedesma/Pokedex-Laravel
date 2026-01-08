<?php

declare(strict_types=1);

namespace App\Infrastructure\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use RuntimeException;

/**
 * PokéAPI HTTP Client
 * 
 * Handles all HTTP communication with the PokéAPI.
 * Single Responsibility: HTTP communication with PokéAPI.
 */
final class PokeApiClient
{
    private string $baseUrl;
    private int $timeout;
    private int $cacheTtl;

    public function __construct()
    {
        $this->baseUrl = (string) config('services.pokeapi.base_url', 'https://pokeapi.co/api/v2');
        $this->timeout = (int) config('services.pokeapi.timeout', 30);
        $this->cacheTtl = (int) config('services.pokeapi.cache_ttl', 3600);
    }

    /**
     * Get Pokemon by ID
     *
     * @param int $id
     * @return array<string, mixed>|null
     */
    public function getPokemonById(int $id): ?array
    {
        $cacheKey = "pokemon:id:{$id}";

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($id) {
            return $this->makeRequest("/pokemon/{$id}");
        });
    }

    /**
     * Get Pokemon by name
     *
     * @param string $name
     * @return array<string, mixed>|null
     */
    public function getPokemonByName(string $name): ?array
    {
        $cacheKey = "pokemon:name:" . strtolower($name);

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($name) {
            return $this->makeRequest("/pokemon/" . strtolower($name));
        });
    }

    /**
     * Get list of Pokemon
     *
     * @param int $limit
     * @param int $offset
     * @return array<string, mixed>|null
     */
    public function getPokemonList(int $limit = 20, int $offset = 0): ?array
    {
        $cacheKey = "pokemon:list:{$limit}:{$offset}";

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($limit, $offset) {
            return $this->makeRequest("/pokemon", [
                'limit' => $limit,
                'offset' => $offset,
            ]);
        });
    }

    /**
     * Make HTTP request to PokéAPI
     *
     * @param string $endpoint
     * @param array<string, mixed> $params
     * @return array<string, mixed>|null
     */
    private function makeRequest(string $endpoint, array $params = []): ?array
    {
        try {
            $url = $this->baseUrl . $endpoint;
            
            $response = Http::withoutVerifying() // Deshabilitar verificación SSL (solo desarrollo)
                ->timeout($this->timeout)
                ->retry(3, 100)
                ->get($url, $params);

            if ($response->successful()) {
                return $response->json();
            }

            if ($response->status() === 404) {
                return null;
            }

            Log::error('PokéAPI request failed', [
                'endpoint' => $endpoint,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new RuntimeException(
                "PokéAPI request failed with status {$response->status()}"
            );
        } catch (\Exception $e) {
            Log::error('PokéAPI request exception', [
                'endpoint' => $endpoint,
                'error' => $e->getMessage(),
            ]);

            throw new RuntimeException(
                "Failed to fetch data from PokéAPI: {$e->getMessage()}",
                0,
                $e
            );
        }
    }
}

