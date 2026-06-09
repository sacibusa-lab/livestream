@extends('layouts.app')

@section('title', "{$fixture->home_team} vs {$fixture->away_team} – Live Stream")
@section('meta_description', "Watch the live stream for {$fixture->home_team} vs {$fixture->away_team} in the 2026 World Cup.")

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('fixtures.list') }}" class="text-gray-400 hover:text-white flex items-center gap-1 text-sm font-medium transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Fixtures
        </a>
    </div>

    {{-- Match Header Card --}}
    <div class="glass rounded-2xl p-6 md:p-8 mb-8">
        <div class="flex flex-col md:flex-row items-center justify-between gap-8">
            {{-- Home Team --}}
            <div class="flex flex-col items-center gap-3 w-full md:w-1/3">
                @if($fixture->home_team_flag_url)
                    <img src="{{ $fixture->home_team_flag_url }}" alt="{{ $fixture->home_team }}" class="w-24 md:w-32 h-auto object-cover rounded shadow-lg border border-white/10">
                @else
                    <div class="w-24 md:w-32 h-16 bg-gray-800 rounded shadow-lg border border-white/10"></div>
                @endif
                <h2 class="text-xl md:text-3xl font-display font-bold text-center">{{ $fixture->home_team }}</h2>
            </div>

            {{-- Score / Details --}}
            <div class="flex flex-col items-center w-full md:w-1/3 text-center shrink-0">
                <div class="text-sm text-brand-500 font-semibold mb-2 tracking-widest uppercase">
                    {{ $fixture->stage }} {{ $fixture->group ? ' • ' . $fixture->group : '' }}
                </div>
                
                @if($fixture->status === 'live')
                    <div class="mb-3 inline-flex items-center px-3 py-1 bg-red-900/50 text-red-400 text-xs font-bold rounded-full border border-red-500/30">
                        <span class="w-2 h-2 bg-red-500 rounded-full mr-2 animate-pulse"></span>LIVE NOW
                    </div>
                @elseif($fixture->status === 'upcoming')
                    <div class="mb-3 inline-flex items-center px-3 py-1 bg-blue-900/50 text-blue-300 text-xs font-bold rounded-full border border-blue-500/30">
                        UPCOMING
                    </div>
                @else
                    <div class="mb-3 inline-flex items-center px-3 py-1 bg-gray-800 text-gray-400 text-xs font-bold rounded-full border border-gray-600/50">
                        FINISHED
                    </div>
                @endif

                @if($fixture->home_score !== null && $fixture->away_score !== null)
                    <div class="text-4xl md:text-6xl font-display font-black text-white tracking-wider my-2">
                        {{ $fixture->home_score }} - {{ $fixture->away_score }}
                    </div>
                @else
                    <div class="text-2xl font-bold text-gray-500 my-4">VS</div>
                @endif

                <div class="text-gray-400 text-sm mt-3 flex flex-col items-center gap-1">
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ $fixture->match_time->format('l, M d, Y • H:i') }}
                    </div>
                    @if($fixture->venue)
                        <div class="flex items-center gap-1.5 mt-1">
                            <svg class="w-4 h-4 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $fixture->venue }}
                        </div>
                    @endif
                </div>
            </div>

            {{-- Away Team --}}
            <div class="flex flex-col items-center gap-3 w-full md:w-1/3">
                @if($fixture->away_team_flag_url)
                    <img src="{{ $fixture->away_team_flag_url }}" alt="{{ $fixture->away_team }}" class="w-24 md:w-32 h-auto object-cover rounded shadow-lg border border-white/10">
                @else
                    <div class="w-24 md:w-32 h-16 bg-gray-800 rounded shadow-lg border border-white/10"></div>
                @endif
                <h2 class="text-xl md:text-3xl font-display font-bold text-center">{{ $fixture->away_team }}</h2>
            </div>
        </div>
    </div>

    {{-- AI Match Content Section --}}
    @if($fixture->status === 'finished' && $fixture->recap_content)
        <div class="glass rounded-2xl p-6 md:p-8 mb-8 animate-fade-in border-t-4 border-indigo-500">
            <h3 class="text-xl font-display font-bold text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                Post-Match Recap
            </h3>
            <div class="prose prose-invert prose-brand max-w-none text-gray-300">
                {!! \Illuminate\Support\Str::markdown($fixture->recap_content) !!}
            </div>
        </div>
    @elseif($fixture->status !== 'finished' && $fixture->preview_content)
        <div class="glass rounded-2xl p-6 md:p-8 mb-8 animate-fade-in border-t-4 border-purple-500">
            <h3 class="text-xl font-display font-bold text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Match Preview
            </h3>
            <div class="prose prose-invert prose-brand max-w-none text-gray-300">
                {!! \Illuminate\Support\Str::markdown($fixture->preview_content) !!}
            </div>
        </div>
    @endif

    {{-- Live Stream Section --}}
    @if($fixture->status !== 'upcoming')
        <div class="relative {{ ($streamProvider === 'owncast' && $owncastUrl && $owncastChatEnabled) ? 'max-w-6xl' : 'max-w-4xl' }} mx-auto rounded-2xl overflow-hidden shadow-2xl ring-1 ring-white/10 animate-slide-up">
            @if($streamProvider === 'owncast' && $owncastUrl)
                @if($owncastChatEnabled)
                    <div class="grid grid-cols-1 lg:grid-cols-3 bg-black">
                        {{-- Video Column --}}
                        <div class="lg:col-span-2 aspect-video bg-black">
                            <iframe src="{{ rtrim($owncastUrl, '/') }}/embed/video" class="w-full h-full" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                        {{-- Chat Column --}}
                        <div class="h-[400px] lg:h-auto border-t lg:border-t-0 lg:border-l border-white/10 bg-[#161b22]">
                            <iframe src="{{ rtrim($owncastUrl, '/') }}/embed/chat" class="w-full h-full" frameborder="0"></iframe>
                        </div>
                    </div>
                @else
                    <div class="aspect-video bg-black">
                        <iframe src="{{ rtrim($owncastUrl, '/') }}/embed/video" class="w-full h-full" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                @endif
            @elseif($streamProvider === 'standard')
                <div class="aspect-video bg-gradient-to-br from-gray-900 to-dark-950 flex flex-col items-center justify-center gap-4">
                    <div class="text-center text-gray-500">
                        <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        <p class="text-lg">Stream not available for this match.</p>
                        <p class="text-sm mt-1">Please check the main homepage stream or try again later.</p>
                    </div>
                </div>
            @endif
        </div>
    @else
        <div class="max-w-4xl mx-auto aspect-video bg-dark-950 rounded-2xl border border-white/5 flex flex-col items-center justify-center p-8 text-center mt-8 shadow-xl">
            <svg class="w-16 h-16 text-gray-700 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <h3 class="text-xl font-bold text-gray-300 mb-2">Match is Upcoming</h3>
            <p class="text-gray-500 max-w-md">The live stream will be available here when the match is about to start.</p>
        </div>
    @endif
</div>
@endsection
