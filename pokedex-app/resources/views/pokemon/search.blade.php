@extends('layouts.app')

@section('title', 'Search Results')

@section('content')
<div class="mb-8">
    <h1 class="text-4xl font-bold text-gray-800 mb-2">Search Results</h1>
    <p class="text-gray-600">
        Showing results for: <span class="font-semibold text-purple-600">"{{ $query }}"</span>
    </p>
</div>

@if(empty($results))
    <div class="bg-white rounded-lg shadow-md p-8 text-center">
        <div class="text-6xl mb-4">😞</div>
        <h2 class="text-2xl font-semibold text-gray-800 mb-2">No Pokémon Found</h2>
        <p class="text-gray-600 mb-6">
            We couldn't find any Pokémon matching "{{ $query }}".
        </p>
        <a href="{{ route('pokemon.index') }}" class="inline-block bg-purple-600 hover:bg-purple-700 text-white font-semibold px-6 py-3 rounded-lg transition">
            Browse All Pokémon
        </a>
    </div>
@else
    <!-- Results Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8">
        @foreach($results as $pokemon)
            <a href="{{ route('pokemon.show', $pokemon->id) }}" class="pokemon-card bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl">
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-6 flex items-center justify-center">
                    <img 
                        src="{{ $pokemon->imageUrl }}" 
                        alt="{{ $pokemon->name }}"
                        class="w-32 h-32 object-contain"
                    >
                </div>
                <div class="p-4">
                    <div class="text-gray-500 text-sm font-semibold mb-1">#{{ str_pad($pokemon->id, 3, '0', STR_PAD_LEFT) }}</div>
                    <h3 class="text-xl font-bold text-gray-800 capitalize">{{ $pokemon->name }}</h3>
                </div>
            </a>
        @endforeach
    </div>

    <div class="text-center">
        <a href="{{ route('pokemon.index') }}" class="inline-block bg-white hover:bg-purple-600 hover:text-white text-purple-600 font-semibold px-6 py-3 rounded-lg shadow transition">
            ← Back to All Pokémon
        </a>
    </div>
@endif
@endsection

