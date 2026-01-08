@extends('layouts.app')

@section('title', $pokemon->name)

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Back Button -->
    <a href="{{ route('pokemon.index') }}" class="inline-flex items-center text-purple-600 hover:text-purple-800 mb-6 transition">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Back to Pokédex
    </a>

    <!-- Pokemon Card -->
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
        <!-- Header with gradient -->
        <div class="gradient-bg p-8 text-white">
            <div class="flex items-start justify-between">
                <div>
                    <div class="text-white/80 text-lg font-semibold mb-2">
                        #{{ str_pad($pokemon->id, 3, '0', STR_PAD_LEFT) }}
                    </div>
                    <h1 class="text-5xl font-bold capitalize mb-4">{{ $pokemon->name }}</h1>
                    
                    <!-- Types -->
                    <div class="flex space-x-2">
                        @foreach($pokemon->types as $type)
                            <span class="type-badge px-4 py-2 rounded-full text-sm font-semibold bg-white/20 backdrop-blur">
                                {{ $type }}
                            </span>
                        @endforeach
                    </div>
                </div>
                
                <!-- Pokemon Image -->
                <div class="bg-white/10 backdrop-blur rounded-2xl p-6">
                    <img 
                        src="{{ $pokemon->imageUrl }}" 
                        alt="{{ $pokemon->name }}"
                        class="w-48 h-48 object-contain"
                    >
                </div>
            </div>
        </div>

        <!-- Body -->
        <div class="p-8">
            <!-- Physical Attributes -->
            <div class="grid grid-cols-2 gap-4 mb-8">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 text-center">
                    <div class="text-4xl mb-2">📏</div>
                    <div class="text-gray-600 text-sm font-semibold mb-1">Height</div>
                    <div class="text-2xl font-bold text-gray-800">{{ number_format($pokemon->height, 1) }} m</div>
                </div>
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 text-center">
                    <div class="text-4xl mb-2">⚖️</div>
                    <div class="text-gray-600 text-sm font-semibold mb-1">Weight</div>
                    <div class="text-2xl font-bold text-gray-800">{{ number_format($pokemon->weight, 1) }} kg</div>
                </div>
            </div>

            <!-- Stats -->
            <div>
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Base Stats</h2>
                
                <div class="space-y-4">
                    @php
                        $statLabels = [
                            'hp' => 'HP',
                            'attack' => 'Attack',
                            'defense' => 'Defense',
                            'specialAttack' => 'Sp. Attack',
                            'specialDefense' => 'Sp. Defense',
                            'speed' => 'Speed',
                        ];
                        
                        $maxStat = 255;
                    @endphp
                    
                    @foreach($statLabels as $key => $label)
                        @php
                            $value = $pokemon->stats[$key];
                            $percentage = ($value / $maxStat) * 100;
                            
                            // Color based on stat value
                            if ($value >= 100) {
                                $color = 'bg-green-500';
                            } elseif ($value >= 70) {
                                $color = 'bg-blue-500';
                            } elseif ($value >= 50) {
                                $color = 'bg-yellow-500';
                            } else {
                                $color = 'bg-red-500';
                            }
                        @endphp
                        
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-700 font-semibold w-32">{{ $label }}</span>
                                <span class="text-gray-900 font-bold">{{ $value }}</span>
                            </div>
                            <div class="bg-gray-200 rounded-full h-3 overflow-hidden">
                                <div class="stat-bar {{ $color }} h-full rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                    
                    <!-- Total -->
                    <div class="pt-4 border-t-2 border-gray-200">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-800 font-bold text-lg">Total</span>
                            <span class="text-purple-600 font-bold text-2xl">{{ $pokemon->stats['total'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

