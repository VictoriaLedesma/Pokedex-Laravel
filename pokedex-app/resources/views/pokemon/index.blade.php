@extends('layouts.app')

@section('title', 'All Pokémon')

@section('content')
<div class="mb-8">
    <h1 class="text-4xl font-bold text-gray-800 mb-2">Discover Pokémon</h1>
    <p class="text-gray-600">Explore the wonderful world of Pokémon</p>
</div>

@if(empty($pokemon))
    <div class="bg-white rounded-lg shadow-md p-8 text-center">
        <div class="text-6xl mb-4">🔍</div>
        <h2 class="text-2xl font-semibold text-gray-800 mb-2">No Pokémon Found</h2>
        <p class="text-gray-600">Try adjusting your search or check back later.</p>
    </div>
@else
    <!-- Pokemon Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8">
        @foreach($pokemon as $p)
            <a href="{{ route('pokemon.show', $p->id) }}" class="pokemon-card bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl">
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-6 flex items-center justify-center">
                    <img 
                        src="{{ $p->imageUrl }}" 
                        alt="{{ $p->name }}"
                        class="w-32 h-32 object-contain"
                        loading="lazy"
                    >
                </div>
                <div class="p-4">
                    <div class="text-gray-500 text-sm font-semibold mb-1">#{{ str_pad($p->id, 3, '0', STR_PAD_LEFT) }}</div>
                    <h3 class="text-xl font-bold text-gray-800 capitalize">{{ $p->name }}</h3>
                </div>
            </a>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="flex justify-center items-center space-x-4">
        @if($currentPage > 1)
            <a href="{{ route('pokemon.index', ['page' => $currentPage - 1]) }}" 
               class="bg-white hover:bg-purple-600 hover:text-white text-purple-600 font-semibold px-6 py-2 rounded-lg shadow transition">
                ← Previous
            </a>
        @else
            <span class="bg-gray-200 text-gray-500 font-semibold px-6 py-2 rounded-lg cursor-not-allowed">
                ← Previous
            </span>
        @endif

        <span class="bg-purple-600 text-white font-semibold px-6 py-2 rounded-lg shadow">
            Page {{ $currentPage }}
        </span>

        @if($hasMore)
            <a href="{{ route('pokemon.index', ['page' => $currentPage + 1]) }}" 
               class="bg-white hover:bg-purple-600 hover:text-white text-purple-600 font-semibold px-6 py-2 rounded-lg shadow transition">
                Next →
            </a>
        @else
            <span class="bg-gray-200 text-gray-500 font-semibold px-6 py-2 rounded-lg cursor-not-allowed">
                Next →
            </span>
        @endif
    </div>
@endif
@endsection

