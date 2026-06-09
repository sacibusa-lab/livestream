@extends('layouts.app')

@section('title', $post->title . ' | 2026WORLDCUP.com.ng')
@section('meta_description', Str::limit(strip_tags($post->body), 155))

@section('content')
<article class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 animate-fade-in">
    {{-- Featured Image --}}
    @if($post->featured_image)
        <div class="mb-8 rounded-2xl overflow-hidden shadow-2xl ring-1 ring-white/10">
            <img src="{{ $post->featured_image }}"
                 alt="{{ $post->title }}"
                 class="w-full max-h-[480px] object-cover"
                 onerror="this.parentElement.style.display='none'">
        </div>
    @endif

    {{-- Meta --}}
    <div class="flex flex-wrap items-center gap-3 mb-4">
        <span class="text-xs font-semibold text-brand-500 bg-brand-500/10 border border-brand-500/20 px-3 py-1 rounded-full uppercase tracking-wide">
            {{ $post->category }}
        </span>
        <time class="text-gray-500 text-sm">
            {{ $post->published_at?->format('F j, Y') }}
        </time>
    </div>

    {{-- Title --}}
    <h1 class="font-display text-3xl sm:text-4xl lg:text-5xl font-bold text-white leading-tight mb-8">
        {{ $post->title }}
    </h1>

    {{-- Divider --}}
    <div class="h-px bg-gradient-to-r from-brand-500/50 via-white/10 to-transparent mb-8"></div>

    {{-- Body --}}
    <div class="prose prose-lg prose-invert max-w-none
                prose-headings:font-display prose-headings:text-white
                prose-p:text-gray-300 prose-p:leading-relaxed
                prose-a:text-brand-400 prose-a:no-underline hover:prose-a:text-brand-300
                prose-strong:text-white prose-strong:font-semibold
                prose-blockquote:border-brand-500 prose-blockquote:text-gray-400
                prose-code:text-brand-400 prose-code:bg-gray-900 prose-code:rounded
                prose-img:rounded-xl prose-img:shadow-2xl">
        {!! $post->body !!}
    </div>

    {{-- Back link --}}
    <div class="mt-12 pt-8 border-t border-white/10">
        <a href="{{ route('home') }}"
           class="inline-flex items-center gap-2 text-gray-400 hover:text-white transition-colors group">
            <svg class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Homepage
        </a>
    </div>
</article>
@endsection
