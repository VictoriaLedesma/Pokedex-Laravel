<?php

declare(strict_types=1);

use App\Http\Controllers\PokemonController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
|
*/

Route::get('/', [PokemonController::class, 'index'])->name('pokemon.index');
Route::get('/pokemon', [PokemonController::class, 'index'])->name('pokemon.list');
Route::get('/pokemon/search', [PokemonController::class, 'search'])->name('pokemon.search');
Route::get('/pokemon/{identifier}', [PokemonController::class, 'show'])->name('pokemon.show');

