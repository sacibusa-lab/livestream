@extends('layouts.admin')

@section('title', $post ? 'Edit Post' : 'Create Post')
@section('page_title', $post ? 'Edit Post' : 'Create New Post')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sm:p-8">
        <form method="POST"
              action="{{ $post ? route('admin.posts.update', $post->id) : route('admin.posts.store') }}"
              id="post-form">
            @csrf
            @if($post)
                @method('PUT')
            @endif

            {{-- Validation errors --}}
            @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                    <p class="text-red-700 text-sm font-semibold mb-2">Please fix the following errors:</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li class="text-red-600 text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="space-y-5">
                {{-- Title --}}
                <div>
                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="title"
                           name="title"
                           value="{{ old('title', $post?->title) }}"
                           required
                           maxlength="255"
                           placeholder="Enter post title..."
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm text-gray-800 focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 outline-none transition-all">
                </div>

                {{-- Category & Status row --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="category" class="block text-sm font-semibold text-gray-700 mb-1.5">Category</label>
                        <input type="text"
                               id="category"
                               name="category"
                               value="{{ old('category', $post?->category ?? 'News') }}"
                               maxlength="100"
                               placeholder="News, Match Report, Opinion..."
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm text-gray-800 focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 outline-none transition-all">
                    </div>
                    <div>
                        <label for="published_at" class="block text-sm font-semibold text-gray-700 mb-1.5">Publish Date</label>
                        <input type="datetime-local"
                               id="published_at"
                               name="published_at"
                               value="{{ old('published_at', $post?->published_at?->format('Y-m-d\TH:i') ?? now()->format('Y-m-d\TH:i')) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm text-gray-800 focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 outline-none transition-all">
                    </div>
                </div>

                {{-- Featured Image URL --}}
                <div>
                    <label for="featured_image" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Featured Image URL
                        <span class="text-gray-400 font-normal">(optional)</span>
                    </label>
                    <input type="url"
                           id="featured_image"
                           name="featured_image"
                           value="{{ old('featured_image', $post?->featured_image) }}"
                           placeholder="https://example.com/image.jpg"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm text-gray-800 focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 outline-none transition-all">
                    {{-- Preview --}}
                    <div id="img-preview" class="{{ old('featured_image', $post?->featured_image) ? '' : 'hidden' }} mt-2">
                        <img id="img-preview-el" src="{{ old('featured_image', $post?->featured_image) }}"
                             alt="Preview" class="h-32 rounded-lg object-cover border border-gray-200"
                             onerror="this.parentElement.classList.add('hidden')">
                    </div>
                </div>

                {{-- Body --}}
                <div>
                    <label for="body" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Content <span class="text-red-500">*</span>
                    </label>
                    <div class="border border-gray-300 rounded-lg overflow-hidden focus-within:ring-2 focus-within:ring-orange-500/30 focus-within:border-orange-500 transition-all">
                        {{-- Simple toolbar --}}
                        <div class="bg-gray-50 border-b border-gray-200 px-3 py-2 flex flex-wrap gap-1">
                            @foreach([
                                ['cmd' => 'bold',         'icon' => '<strong>B</strong>',  'title' => 'Bold'],
                                ['cmd' => 'italic',       'icon' => '<em>I</em>',          'title' => 'Italic'],
                                ['cmd' => 'underline',    'icon' => '<u>U</u>',            'title' => 'Underline'],
                            ] as $btn)
                            <button type="button"
                                    onclick="document.execCommand('{{ $btn['cmd'] }}')"
                                    title="{{ $btn['title'] }}"
                                    class="w-8 h-8 text-gray-600 hover:bg-gray-200 rounded text-sm flex items-center justify-center transition-colors">
                                {!! $btn['icon'] !!}
                            </button>
                            @endforeach
                            <div class="w-px bg-gray-200 mx-1 self-stretch"></div>
                            <button type="button" onclick="document.execCommand('insertOrderedList')"
                                    title="Ordered list" class="w-8 h-8 text-gray-600 hover:bg-gray-200 rounded text-xs flex items-center justify-center transition-colors">OL</button>
                            <button type="button" onclick="document.execCommand('insertUnorderedList')"
                                    title="Unordered list" class="w-8 h-8 text-gray-600 hover:bg-gray-200 rounded text-xs flex items-center justify-center transition-colors">UL</button>
                            <button type="button" onclick="insertLink()"
                                    title="Link" class="w-8 h-8 text-gray-600 hover:bg-gray-200 rounded text-xs flex items-center justify-center transition-colors">🔗</button>
                            <div class="w-px bg-gray-200 mx-1 self-stretch"></div>
                            <select onchange="formatBlock(this.value); this.value=''" class="text-xs text-gray-600 bg-transparent border-0 outline-none cursor-pointer">
                                <option value="">Heading</option>
                                <option value="h2">H2</option>
                                <option value="h3">H3</option>
                                <option value="h4">H4</option>
                                <option value="p">Paragraph</option>
                            </select>
                        </div>
                        <div id="editor"
                             contenteditable="true"
                             class="min-h-[300px] px-4 py-3 text-gray-800 text-sm leading-relaxed focus:outline-none prose prose-sm max-w-none">
                            {!! old('body', $post?->body) !!}
                        </div>
                    </div>
                    <input type="hidden" id="body" name="body">
                </div>

                {{-- Published toggle --}}
                <div class="flex items-center gap-3 py-2">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox"
                               id="is_published"
                               name="is_published"
                               value="1"
                               class="sr-only peer"
                               {{ old('is_published', $post?->is_published ?? true) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:ring-2 peer-focus:ring-orange-500/30 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-500"></div>
                    </label>
                    <label for="is_published" class="text-sm font-medium text-gray-700 cursor-pointer">
                        Published
                        <span class="text-gray-400 font-normal">(uncheck to save as draft)</span>
                    </label>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 mt-8 pt-6 border-t border-gray-100">
                <button type="submit"
                        id="post-submit"
                        class="inline-flex items-center gap-2 bg-orange-600 hover:bg-orange-500 text-white font-semibold px-6 py-3 rounded-lg text-sm transition-all shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ $post ? 'Update Post' : 'Publish Post' }}
                </button>
                <a href="{{ route('admin.posts.index') }}"
                   class="text-sm text-gray-500 hover:text-gray-700 transition-colors px-4 py-3">
                    Cancel
                </a>
                @if($post)
                    <a href="{{ route('post.show', $post->slug) }}" target="_blank"
                       class="ml-auto text-sm text-blue-600 hover:text-blue-500 transition-colors flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        View post
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Sync contenteditable editor to hidden textarea on submit
    document.getElementById('post-form').addEventListener('submit', function() {
        document.getElementById('body').value = document.getElementById('editor').innerHTML;
    });

    function formatBlock(tag) {
        if (tag) document.execCommand('formatBlock', false, tag);
    }

    function insertLink() {
        const url = prompt('Enter URL:');
        if (url) document.execCommand('createLink', false, url);
    }

    // Image URL preview
    document.getElementById('featured_image').addEventListener('input', function() {
        const val = this.value.trim();
        const preview = document.getElementById('img-preview');
        const img = document.getElementById('img-preview-el');
        if (val) {
            img.src = val;
            preview.classList.remove('hidden');
        } else {
            preview.classList.add('hidden');
        }
    });
</script>
@endpush
@endsection
