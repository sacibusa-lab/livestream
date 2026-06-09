@extends('layouts.admin')

@section('title', 'Blog Posts')
@section('page_title', 'Blog Posts')

@section('content')
<div class="space-y-5">
    {{-- Header bar --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
        <p class="text-gray-500 text-sm">{{ $posts->total() }} total posts</p>
        <a href="{{ route('admin.posts.create') }}"
           class="inline-flex items-center gap-2 bg-orange-600 hover:bg-orange-500 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Create New Post
        </a>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left text-gray-500 font-semibold px-5 py-3.5 text-xs uppercase tracking-wide">Title</th>
                        <th class="text-left text-gray-500 font-semibold px-4 py-3.5 text-xs uppercase tracking-wide hidden sm:table-cell">Category</th>
                        <th class="text-left text-gray-500 font-semibold px-4 py-3.5 text-xs uppercase tracking-wide hidden md:table-cell">Status</th>
                        <th class="text-left text-gray-500 font-semibold px-4 py-3.5 text-xs uppercase tracking-wide hidden lg:table-cell">Date</th>
                        <th class="text-right text-gray-500 font-semibold px-5 py-3.5 text-xs uppercase tracking-wide">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($posts as $post)
                    <tr class="hover:bg-gray-50/50 transition-colors" id="post-row-{{ $post->id }}">
                        <td class="px-5 py-4">
                            <div>
                                <a href="{{ route('post.show', $post->slug) }}" target="_blank"
                                   class="font-medium text-gray-800 hover:text-orange-600 transition-colors line-clamp-1">
                                    {{ $post->title }}
                                </a>
                                <p class="text-gray-400 text-xs mt-0.5 sm:hidden">{{ $post->category }}</p>
                            </div>
                        </td>
                        <td class="px-4 py-4 hidden sm:table-cell">
                            <span class="text-gray-600">{{ $post->category }}</span>
                        </td>
                        <td class="px-4 py-4 hidden md:table-cell">
                            <span class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-full
                                {{ $post->is_published ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-gray-100 text-gray-500 border border-gray-200' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $post->is_published ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                                {{ $post->is_published ? 'Published' : 'Draft' }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-gray-400 text-xs hidden lg:table-cell">
                            {{ $post->published_at?->format('M j, Y') }}
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.posts.edit', $post->id) }}"
                                   class="inline-flex items-center gap-1 text-xs font-medium text-blue-600 hover:text-blue-500 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Edit
                                </a>
                                <button type="button"
                                        onclick="confirmDelete({{ $post->id }}, '{{ addslashes($post->title) }}')"
                                        class="inline-flex items-center gap-1 text-xs font-medium text-red-600 hover:text-red-500 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Delete
                                </button>

                                {{-- Hidden delete form --}}
                                <form id="delete-form-{{ $post->id }}"
                                      method="POST"
                                      action="{{ route('admin.posts.destroy', $post->id) }}"
                                      class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-16 text-center text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0120 9.586V19a2 2 0 01-2 2z"/></svg>
                            <p class="font-medium">No posts yet.</p>
                            <a href="{{ route('admin.posts.create') }}" class="text-orange-600 text-sm hover:underline mt-1 block">Create your first post →</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($posts->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $posts->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div id="delete-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl p-6 max-w-sm w-full mx-4">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900">Delete Post</h3>
                <p class="text-gray-500 text-sm" id="delete-modal-msg">Are you sure?</p>
            </div>
        </div>
        <div class="flex gap-3 mt-5">
            <button onclick="closeDeleteModal()"
                    class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                Cancel
            </button>
            <button id="delete-confirm-btn"
                    class="flex-1 px-4 py-2.5 text-sm font-medium text-white bg-red-600 hover:bg-red-500 rounded-lg transition-colors">
                Delete
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let pendingDeleteId = null;

    function confirmDelete(id, title) {
        pendingDeleteId = id;
        document.getElementById('delete-modal-msg').textContent = 'Delete "' + title + '"? This cannot be undone.';
        document.getElementById('delete-modal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        pendingDeleteId = null;
        document.getElementById('delete-modal').classList.add('hidden');
    }

    document.getElementById('delete-confirm-btn').addEventListener('click', function() {
        if (pendingDeleteId) {
            document.getElementById('delete-form-' + pendingDeleteId).submit();
        }
    });

    document.getElementById('delete-modal').addEventListener('click', function(e) {
        if (e.target === this) closeDeleteModal();
    });
</script>
@endpush
@endsection
