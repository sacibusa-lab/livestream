<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Login – 2026WORLDCUP.com.ng</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Oswald:wght@600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'], display: ['Oswald', 'sans-serif'] },
                    colors: { brand: { 500: '#f97316', 600: '#ea580c' } }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .input-field { transition: border-color 0.2s, box-shadow 0.2s; }
        .input-field:focus { border-color: #f97316; box-shadow: 0 0 0 3px rgba(249,115,22,0.15); outline: none; }
    </style>
</head>
<body class="min-h-screen bg-gray-950 flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-block">
                <span class="font-display text-2xl font-bold">
                    <span class="text-white">2026</span><span class="text-brand-500">WORLDCUP</span><span class="text-gray-600 font-normal text-base">.com.ng</span>
                </span>
            </a>
            <p class="text-gray-500 text-sm mt-2">Admin Portal</p>
        </div>

        {{-- Card --}}
        <div class="bg-gray-900 border border-white/10 rounded-2xl shadow-2xl p-8">
            <h1 class="text-white text-xl font-semibold mb-6">Sign in to continue</h1>

            {{-- Validation errors --}}
            @if($errors->any())
                <div class="mb-5 bg-red-900/40 border border-red-500/30 rounded-lg px-4 py-3">
                    @foreach($errors->all() as $error)
                        <p class="text-red-400 text-sm flex items-start gap-2">
                            <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $error }}
                        </p>
                    @endforeach
                </div>
            @endif

            <form id="admin-login-form" method="POST" action="{{ route('admin.login.post') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-gray-400 text-sm font-medium mb-1.5">Email address</label>
                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           autocomplete="email"
                           placeholder="admin@2026worldcup.com.ng"
                           class="input-field w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-3 text-sm placeholder-gray-600">
                </div>

                <div>
                    <label for="password" class="block text-gray-400 text-sm font-medium mb-1.5">Password</label>
                    <input type="password"
                           id="password"
                           name="password"
                           required
                           autocomplete="current-password"
                           placeholder="••••••••"
                           class="input-field w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-3 text-sm placeholder-gray-600">
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" id="remember" name="remember" class="rounded border-gray-600 bg-gray-800 text-brand-500">
                    <label for="remember" class="text-gray-400 text-sm cursor-pointer">Remember me</label>
                </div>

                <button type="submit"
                        id="login-submit"
                        class="w-full bg-brand-600 hover:bg-brand-500 text-white font-semibold rounded-lg px-4 py-3 text-sm transition-all duration-200 hover:shadow-lg hover:shadow-brand-600/30 active:scale-[0.98]">
                    Sign In
                </button>
            </form>
        </div>

        <p class="text-center text-gray-700 text-xs mt-6">
            <a href="{{ route('home') }}" class="hover:text-gray-500 transition-colors">← Back to site</a>
        </p>
    </div>
</body>
</html>
