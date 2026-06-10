<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="@yield('meta_description', 'Your #1 source for live World Cup 2026 coverage and breaking football news from Nigeria.')">
    <title>@yield('title', '2026WORLDCUP.com.ng – Watch Live')</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Oswald:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Oswald', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50:  '#fff7ed',
                            100: '#ffedd5',
                            500: '#f97316',
                            600: '#ea580c',
                            700: '#c2410c',
                            900: '#7c2d12',
                        },
                        dark: {
                            800: '#111827',
                            900: '#0a0f1a',
                            950: '#060912',
                        }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.4s ease-out',
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        fadeIn: { '0%': { opacity: 0 }, '100%': { opacity: 1 } },
                        slideUp: { '0%': { opacity: 0, transform: 'translateY(20px)' }, '100%': { opacity: 1, transform: 'translateY(0)' } },
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Oswald', sans-serif; }
        .gradient-brand { background: linear-gradient(135deg, #ea580c 0%, #f97316 50%, #fbbf24 100%); }
        .gradient-dark  { background: linear-gradient(180deg, #0a0f1a 0%, #111827 100%); }
        .glass { background: rgba(255,255,255,0.05); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.1); }
        .live-dot::before { content:''; display:inline-block; width:8px; height:8px; border-radius:50%; background:#ef4444; margin-right:6px; animation: pulse 1.5s infinite; }
        .card-hover { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(0,0,0,0.3); }
        .btn-primary { transition: all 0.2s ease; }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 8px 25px rgba(249,115,22,0.4); }
    </style>

    @stack('head')
</head>
<body class="bg-dark-900 text-gray-100 min-h-screen flex flex-col antialiased">

    {{-- ===== NAVBAR ===== --}}
    <header class="sticky top-0 z-50 glass border-b border-white/10">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                <span class="font-display text-xl font-bold tracking-wide">
                    <span class="text-white">2026</span><span class="text-brand-500">WORLDCUP</span><span class="text-gray-400 font-light text-sm">.com.ng</span>
                </span>
            </a>

            {{-- Nav links --}}
            <div class="flex items-center gap-3 sm:gap-5 text-sm font-medium">
                <a href="{{ route('home') }}"
                   class="text-gray-300 hover:text-white transition-colors duration-200 hidden sm:inline">Home</a>
                <a href="{{ route('fixtures.list') }}"
                   class="text-gray-300 hover:text-white transition-colors duration-200 hidden sm:inline">Fixtures</a>
                <a href="{{ route('standings') }}"
                   class="text-gray-300 hover:text-white transition-colors duration-200 hidden sm:inline">Standings</a>

                
            </div>
        </nav>
    </header>

    {{-- ===== FLASH MESSAGES ===== --}}
    @if(session('success') || session('error'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 w-full animate-fade-in">
        @if(session('success'))
            <div class="bg-green-900/50 border border-green-500/50 text-green-300 px-4 py-3 rounded-lg text-sm flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-900/50 border border-red-500/50 text-red-300 px-4 py-3 rounded-lg text-sm flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
            </div>
        @endif
    </div>
    @endif

    {{-- ===== MAIN CONTENT ===== --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- ===== FOOTER ===== --}}
    <footer class="border-t border-white/10 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-gray-500">
                <div class="font-display font-semibold text-base">
                    <span class="text-gray-300">2026</span><span class="text-brand-500">WORLDCUP</span><span class="text-gray-500">.com.ng</span>
                </div>
                <p>&copy; {{ date('Y') }} 2026WORLDCUP.com.ng. All rights reserved.</p>
                <p>🇳🇬 Made in Nigeria</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
