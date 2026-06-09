@extends('layouts.app')

@section('title', $siteTitle ?? '2026WORLDCUP.com.ng – Watch Live')
@section('meta_description', 'Watch the 2026 FIFA World Cup live stream and read the latest football news from Nigeria.')

@if($streamType === 'hls')
    @push('head')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/plyr@3.7.8/dist/plyr.css" />
        <script src="https://cdn.jsdelivr.net/npm/plyr@3.7.8/dist/plyr.polyfilled.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/hls.js@1.5.8/dist/hls.min.js"></script>
        <style>
            :root {
                --plyr-color-main: #f97316;
            }
            .plyr {
                border-radius: 1rem;
            }
        </style>
    @endpush
@endif

@section('content')
{{-- ===== HERO + LIVESTREAM SECTION ===== --}}
<section class="relative overflow-hidden bg-dark-950 pt-6 pb-10">
    {{-- Background gradient accents --}}
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-brand-600/20 rounded-full blur-3xl"></div>
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-orange-500/10 rounded-full blur-3xl"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Hero heading --}}
        <div class="text-center mb-6 animate-fade-in">
            <div class="inline-flex items-center gap-2 bg-red-500/20 border border-red-500/40 rounded-full px-4 py-1.5 mb-4">
                <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse-slow"></span>
                <span class="text-red-400 text-xs font-semibold uppercase tracking-widest">Live Coverage</span>
            </div>
            <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl font-bold text-white leading-tight">
                2026 FIFA <span class="text-brand-500">World Cup</span>
            </h1>
            <p class="mt-3 text-gray-400 text-base sm:text-lg max-w-xl mx-auto">
                Watch every match live – straight from Nigeria's #1 football destination.
            </p>
        </div>

        {{-- Livestream embed --}}
        <div class="animate-slide-up">
            <div class="relative {{ ($streamProvider === 'owncast' && $owncastUrl && $owncastChatEnabled) ? 'max-w-6xl' : 'max-w-4xl' }} mx-auto rounded-2xl overflow-hidden shadow-2xl ring-1 ring-white/10">
                @if($streamProvider === 'owncast' && $owncastUrl)
                    @if($owncastChatEnabled)
                        <div class="grid grid-cols-1 lg:grid-cols-3 bg-black">
                            {{-- Video Column --}}
                            <div class="lg:col-span-2 aspect-video bg-black">
                                <iframe
                                    id="owncast-video"
                                    src="{{ rtrim($owncastUrl, '/') }}/embed/video"
                                    class="w-full h-full"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen
                                    referrerpolicy="no-referrer"
                                    title="Live Match Player">
                                </iframe>
                            </div>
                            {{-- Chat Column --}}
                            <div class="h-[350px] lg:h-auto border-t lg:border-t-0 lg:border-l border-white/10 bg-[#161b22] overflow-hidden">
                                <iframe
                                    id="owncast-chat"
                                    src="{{ rtrim($owncastUrl, '/') }}/embed/chat"
                                    class="w-full h-full"
                                    frameborder="0"
                                    referrerpolicy="no-referrer"
                                    title="Live Match Chat">
                                </iframe>
                            </div>
                        </div>
                    @else
                        <div class="aspect-video bg-black">
                            <iframe
                                id="owncast-video-only"
                                src="{{ rtrim($owncastUrl, '/') }}/embed/video"
                                class="w-full h-full"
                                frameborder="0"
                                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen
                                referrerpolicy="no-referrer"
                                title="Live Match Player">
                            </iframe>
                        </div>
                    @endif
                @elseif($streamProvider === 'standard' && $livestreamUrl)
                    @if($streamType === 'hls')
                        <div class="aspect-video bg-black">
                            <video id="hls-player" class="w-full h-full object-contain" controls crossorigin playsinline>
                                <source src="{{ $streamEmbedUrl }}" type="application/x-mpegURL">
                            </video>
                        </div>
                    @else
                        <div class="aspect-video">
                            <iframe
                                id="livestream-player"
                                src="{{ $streamEmbedUrl }}"
                                class="w-full h-full"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                allowfullscreen
                                title="2026 World Cup Live Stream">
                            </iframe>
                        </div>
                    @endif
                @else
                    <div class="aspect-video bg-gradient-to-br from-gray-900 to-dark-950 flex flex-col items-center justify-center gap-4">
                        <div class="w-20 h-20 rounded-full bg-brand-600/20 flex items-center justify-center ring-2 ring-brand-500/30">
                            <svg class="w-10 h-10 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="text-center">
                            <p class="text-white font-semibold text-lg">Livestream Coming Soon</p>
                            <p class="text-gray-500 text-sm mt-1">The match stream will appear here when it goes live.</p>
                        </div>
                        @auth('admin')
                            <a href="{{ route('admin.settings') }}"
                               class="mt-2 inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-all btn-primary">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Set Livestream URL
                            </a>
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- ===== BLOG SECTION ===== --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="font-display text-2xl sm:text-3xl font-bold text-white">Latest News</h2>
            <p class="text-gray-500 text-sm mt-1">World Cup 2026 updates, match reports, and more.</p>
        </div>
        @auth('admin')
            <a href="{{ route('admin.posts.create') }}"
               class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-all btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New Post
            </a>
        @endauth
    </div>

    @if($posts->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($posts as $post)
            <article class="bg-gray-900/60 border border-white/10 rounded-2xl overflow-hidden card-hover animate-fade-in">
                @if($post->featured_image)
                    <a href="{{ route('post.show', $post->slug) }}">
                        <img src="{{ $post->featured_image }}"
                             alt="{{ $post->title }}"
                             class="w-full h-48 object-cover"
                             onerror="this.style.display='none'">
                    </a>
                @else
                    <a href="{{ route('post.show', $post->slug) }}" class="block">
                        <div class="w-full h-48 gradient-brand opacity-60 flex items-center justify-center">
                            <svg class="w-12 h-12 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                            </svg>
                        </div>
                    </a>
                @endif

                <div class="p-5">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-xs font-semibold text-brand-500 bg-brand-500/10 border border-brand-500/20 px-2.5 py-1 rounded-full uppercase tracking-wide">
                            {{ $post->category }}
                        </span>
                        <span class="text-gray-600 text-xs">•</span>
                        <time class="text-gray-500 text-xs">{{ $post->published_at?->format('M j, Y') }}</time>
                    </div>

                    <h3 class="text-white font-semibold text-base leading-snug mb-2 line-clamp-2">
                        <a href="{{ route('post.show', $post->slug) }}" class="hover:text-brand-400 transition-colors">
                            {{ $post->title }}
                        </a>
                    </h3>

                    <p class="text-gray-400 text-sm leading-relaxed line-clamp-3">
                        {{ Str::limit(strip_tags($post->body), 150) }}
                    </p>

                    <a href="{{ route('post.show', $post->slug) }}"
                       class="inline-flex items-center gap-1 text-brand-500 hover:text-brand-400 text-sm font-medium mt-4 transition-colors group">
                        Read more
                        <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </article>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($posts->hasPages())
        <div class="mt-10 flex justify-center">
            {{ $posts->links() }}
        </div>
        @endif

    @else
        <div class="text-center py-20 text-gray-600">
            <svg class="w-16 h-16 mx-auto mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
            </svg>
            <p class="text-gray-500 text-lg font-medium">No articles yet.</p>
            <p class="text-gray-600 text-sm mt-1">Check back soon for the latest World Cup news.</p>
        </div>
    @endif
</section>

@if($streamType === 'hls')
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const video = document.getElementById('hls-player');
                const source = video.getElementsByTagName('source')[0].src;
                
                const defaultOptions = {
                    controls: [
                        'play-large', 'play', 'progress', 'current-time', 
                        'mute', 'volume', 'settings', 'pip', 'fullscreen'
                    ],
                    settings: ['quality', 'speed', 'loop']
                };

                if (Hls.isSupported()) {
                    const hls = new Hls({
                        maxMaxBufferLength: 10,
                        enableWorker: true
                    });
                    hls.loadSource(source);
                    hls.attachMedia(video);
                    
                    hls.on(Hls.Events.MANIFEST_PARSED, function () {
                        video.play().catch(e => console.log('Autoplay blocked:', e));
                    });

                    window.hls = hls;
                } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                    video.src = source;
                }

                // Initialize Plyr
                const player = new Plyr(video, defaultOptions);
            });
        </script>
    @endpush
@endif
@endsection
