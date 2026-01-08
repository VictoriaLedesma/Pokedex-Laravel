<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Pokédex') - Modern Pokémon Encyclopedia</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom Styles -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
        }
        
        .pokemon-card {
            transition: all 0.3s ease;
        }
        
        .pokemon-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        .stat-bar {
            transition: width 0.6s ease;
        }
        
        .type-badge {
            transition: all 0.2s ease;
        }
        
        .type-badge:hover {
            transform: scale(1.05);
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .search-input:focus {
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.3);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navigation -->
    <nav class="gradient-bg shadow-lg">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <a href="{{ route('pokemon.index') }}" class="flex items-center space-x-2">
                    <span class="text-3xl">⚡</span>
                    <span class="text-white text-2xl font-bold">Pokédex</span>
                </a>
                
                <!-- Search Form -->
                <form action="{{ route('pokemon.search') }}" method="GET" class="flex-1 max-w-md mx-8">
                    <div class="relative">
                        <input 
                            type="text" 
                            name="query" 
                            placeholder="Search by name or number..." 
                            value="{{ request('query') }}"
                            class="search-input w-full px-4 py-2 rounded-lg border-2 border-transparent focus:outline-none focus:border-white transition"
                        >
                        <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-purple-600 hover:bg-purple-700 text-white px-4 py-1 rounded-md transition">
                            Search
                        </button>
                    </div>
                </form>
                
                <div class="flex items-center space-x-4">
                    <a href="{{ route('pokemon.index') }}" class="text-white hover:text-purple-200 transition">
                        All Pokémon
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('error'))
        <div class="container mx-auto px-4 mt-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    @if(session('success'))
        <div class="container mx-auto px-4 mt-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-16 py-8">
        <div class="container mx-auto px-4 text-center">
            <p class="text-gray-400">
                Data provided by <a href="https://pokeapi.co/" target="_blank" class="text-purple-400 hover:text-purple-300">PokéAPI</a>
            </p>
            <p class="text-gray-500 text-sm mt-2">
                Built with Laravel, Clean Architecture & SOLID principles
            </p>
        </div>
    </footer>
</body>
</html>

