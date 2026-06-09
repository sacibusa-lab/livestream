@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
<div class="space-y-6">
    {{-- Welcome --}}
    <div class="bg-gradient-to-r from-orange-600 to-orange-500 rounded-2xl p-6 text-white shadow-lg">
        <h2 class="text-xl font-bold mb-1">Welcome back, {{ Auth::guard('admin')->user()?->name }}! 👋</h2>
        <p class="text-orange-100 text-sm">Manage your 2026 World Cup site from here.</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <p class="text-gray-500 text-sm font-medium">Total Posts</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalPosts }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <p class="text-gray-500 text-sm font-medium">Published</p>
            <p class="text-3xl font-bold text-green-600 mt-1">{{ $publishedPosts }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <p class="text-gray-500 text-sm font-medium">Drafts</p>
            <p class="text-3xl font-bold text-gray-400 mt-1">{{ $totalPosts - $publishedPosts }}</p>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <h3 class="font-semibold text-gray-800 mb-4">Quick Actions</h3>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.posts.create') }}"
               class="inline-flex items-center gap-2 bg-orange-600 hover:bg-orange-500 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New Blog Post
            </a>
            <a href="{{ route('admin.posts.index') }}"
               class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-4 py-2.5 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0120 9.586V19a2 2 0 01-2 2z"/></svg>
                Manage Posts
            </a>
            <a href="{{ route('admin.settings') }}"
               class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-4 py-2.5 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                Livestream Settings
            </a>
            <a href="{{ route('home') }}" target="_blank"
               class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-4 py-2.5 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                View Site
            </a>
        </div>
    </div>

    {{-- Recent Posts --}}
    @if($recentPosts->count())
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <h3 class="font-semibold text-gray-800 mb-4">Recent Posts</h3>
        <div class="space-y-3">
            @foreach($recentPosts as $post)
            <div class="flex items-center justify-between py-2.5 border-b border-gray-50 last:border-0">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">{{ $post->title }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $post->category }} · {{ $post->created_at->diffForHumans() }}</p>
                </div>
                <div class="flex items-center gap-3 ml-4 shrink-0">
                    <span class="text-xs px-2 py-0.5 rounded-full {{ $post->is_published ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-500' }}">
                        {{ $post->is_published ? 'Published' : 'Draft' }}
                    </span>
                    <a href="{{ route('admin.posts.edit', $post->id) }}" class="text-xs text-orange-600 hover:text-orange-500 font-medium">Edit</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
