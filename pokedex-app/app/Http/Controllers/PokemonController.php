<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Application\UseCases\GetPokemonDetailUseCase;
use App\Application\UseCases\ListPokemonUseCase;
use App\Application\UseCases\SearchPokemonUseCase;
use App\Http\Requests\SearchPokemonRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * Pokemon Controller
 * 
 * Thin controller that delegates to use cases.
 * Single Responsibility: HTTP request/response handling only.
 * No business logic here.
 */
final class PokemonController
{
    public function __construct(
        private readonly ListPokemonUseCase $listPokemonUseCase,
        private readonly GetPokemonDetailUseCase $getPokemonDetailUseCase,
        private readonly SearchPokemonUseCase $searchPokemonUseCase,
    ) {
    }

    /**
     * Display listing of Pokemon
     */
    public function index(Request $request): View
    {
        $page = max(1, (int) $request->query('page', 1));
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $pokemon = $this->listPokemonUseCase->execute($limit, $offset);
        
        // Debug temporal
        \Log::info('Pokemon loaded', [
            'count' => count($pokemon),
            'page' => $page,
            'limit' => $limit,
            'offset' => $offset
        ]);

        return view('pokemon.index', [
            'pokemon' => $pokemon,
            'currentPage' => $page,
            'hasMore' => count($pokemon) === $limit,
        ]);
    }

    /**
     * Display Pokemon detail
     */
    public function show(string $identifier): View|RedirectResponse
    {
        // Try to get by ID first (if numeric), then by name
        $pokemon = null;
        
        if (is_numeric($identifier)) {
            $pokemon = $this->getPokemonDetailUseCase->executeById((int) $identifier);
        }
        
        if ($pokemon === null) {
            $pokemon = $this->getPokemonDetailUseCase->executeByName($identifier);
        }

        if ($pokemon === null) {
            return redirect()
                ->route('pokemon.index')
                ->with('error', 'Pokemon not found');
        }

        return view('pokemon.show', [
            'pokemon' => $pokemon,
        ]);
    }

    /**
     * Search for Pokemon
     */
    public function search(SearchPokemonRequest $request): View
    {
        $query = $request->validated()['query'];
        $results = $this->searchPokemonUseCase->execute($query);

        return view('pokemon.search', [
            'query' => $query,
            'results' => $results,
        ]);
    }
}

