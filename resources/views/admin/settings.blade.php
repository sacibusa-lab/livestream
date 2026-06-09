@extends('layouts.admin')

@section('title', 'Site Settings')
@section('page_title', 'Site Settings')

@section('content')
<div class="max-w-2xl space-y-6">

    <form method="POST" action="{{ route('admin.settings.update') }}" id="settings-form">
        @csrf

        @if($errors->any())
            <div class="mb-5 bg-red-50 border border-red-200 rounded-lg p-4">
                @foreach($errors->all() as $error)
                    <p class="text-red-600 text-sm">• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        {{-- Segment 1: Livestream Settings --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sm:p-8 mb-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="font-semibold text-gray-900">Livestream Settings</h2>
                    <p class="text-gray-500 text-sm">Manage your default livestream embed for the homepage.</p>
                </div>
            </div>

            <div class="space-y-5">
                {{-- Stream Provider --}}
                <div>
                    <label for="stream_provider" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Stream Provider
                    </label>
                    <select id="stream_provider"
                            name="stream_provider"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm text-gray-800 focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 outline-none transition-all">
                        <option value="standard" {{ old('stream_provider', $streamProvider) === 'standard' ? 'selected' : '' }}>Standard Player (YouTube / HLS)</option>
                        <option value="owncast" {{ old('stream_provider', $streamProvider) === 'owncast' ? 'selected' : '' }}>Owncast (Live Stream + Chat)</option>
                    </select>
                </div>

                {{-- Standard Livestream Fields (YouTube / HLS) --}}
                <div id="standard-stream-fields" class="space-y-4">
                    <div>
                        <label for="livestream_url" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Livestream Embed URL
                        </label>
                        <input type="url"
                               id="livestream_url"
                               name="livestream_url"
                               value="{{ old('livestream_url', $livestreamUrl) }}"
                               placeholder="https://www.youtube.com/watch?v=VIDEO_ID or HLS .m3u8 URL"
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm text-gray-800 focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 outline-none transition-all">
                        <div class="mt-2 p-3 bg-blue-50 border border-blue-100 rounded-lg text-xs text-blue-700 space-y-1">
                            <p class="font-semibold">Supported Streaming Formats:</p>
                            <ul class="list-disc list-inside space-y-0.5 text-blue-600">
                                <li><strong>YouTube:</strong> Paste any standard, share, or live URL</li>
                                <li><strong>OBS / Custom Stream:</strong> Paste your HLS playlist URL</li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Owncast Stream Fields --}}
                <div id="owncast-stream-fields" class="space-y-4">
                    <div>
                        <label for="owncast_url" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Owncast Server URL
                        </label>
                        <input type="url"
                               id="owncast_url"
                               name="owncast_url"
                               value="{{ old('owncast_url', $owncastUrl) }}"
                               placeholder="http://localhost:8080 or https://stream.yourdomain.com"
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm text-gray-800 focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 outline-none transition-all">
                        <p class="text-xs text-gray-500 mt-1">Provide your self-hosted Owncast server root URL.</p>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox"
                               id="owncast_chat_enabled"
                               name="owncast_chat_enabled"
                               value="1"
                               {{ old('owncast_chat_enabled', $owncastChatEnabled) ? 'checked' : '' }}
                               class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded">
                        <label for="owncast_chat_enabled" class="ml-2 block text-sm text-gray-700 font-medium select-none">
                            Enable Owncast Live Chat Widget on Homepage
                        </label>
                    </div>
                </div>
            </div>
        </div>

        {{-- Segment 2: Site Information --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sm:p-8 mb-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="font-semibold text-gray-900">Site Information</h2>
                    <p class="text-gray-500 text-sm">Manage the basic details of your site.</p>
                </div>
            </div>

            <div class="space-y-5">
                {{-- Site Title --}}
                <div>
                    <label for="site_title" class="block text-sm font-semibold text-gray-700 mb-1.5">Site Title</label>
                    <input type="text"
                           id="site_title"
                           name="site_title"
                           value="{{ old('site_title', $siteTitle) }}"
                           maxlength="100"
                           placeholder="2026WORLDCUP.com.ng"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm text-gray-800 focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 outline-none transition-all">
                </div>

                {{-- Site Description --}}
                <div>
                    <label for="site_description" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Site Description
                        <span class="text-gray-400 font-normal">(used for SEO meta tags)</span>
                    </label>
                    <textarea id="site_description"
                              name="site_description"
                              rows="3"
                              maxlength="255"
                              placeholder="Your #1 source for live World Cup 2026 coverage..."
                              class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm text-gray-800 focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 outline-none transition-all resize-none">{{ old('site_description', $siteDesc) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Segment 3: API Integrations --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sm:p-8 mb-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-full bg-purple-50 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                    </svg>
                </div>
                <div>
                    <h2 class="font-semibold text-gray-900">API Integrations</h2>
                    <p class="text-gray-500 text-sm">Configure external services and AI tools.</p>
                </div>
            </div>

            <div class="space-y-5">
                {{-- OpenRouter API Key --}}
                <div>
                    <label for="openrouter_api_key" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        OpenRouter API Key
                    </label>
                    <input type="password"
                           id="openrouter_api_key"
                           name="openrouter_api_key"
                           value="{{ old('openrouter_api_key', $openrouterApiKey ?? '') }}"
                           placeholder="sk-or-v1-..."
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm text-gray-800 focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 outline-none transition-all">
                    <p class="text-xs text-gray-500 mt-1">Required to automatically pull fixtures using AI.</p>
                </div>
            </div>
        </div>

        {{-- Save Button --}}
        <div class="flex items-center gap-3">
            <button type="submit"
                    id="settings-submit"
                    class="inline-flex items-center gap-2 bg-orange-600 hover:bg-orange-500 text-white font-semibold px-6 py-3 rounded-lg text-sm transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Save Settings
            </button>
            <a href="{{ route('home') }}" target="_blank"
               class="text-sm text-gray-500 hover:text-gray-700 transition-colors flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                Preview on Site
            </a>
        </div>
    </form>

    {{-- Current livestream preview --}}
    @if(($streamProvider === 'standard' && $livestreamUrl) || ($streamProvider === 'owncast' && $owncastUrl))
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-semibold text-gray-800 mb-3 text-sm">Current Livestream Preview</h3>
        <div class="aspect-video rounded-lg overflow-hidden bg-gray-100">
            @if($streamProvider === 'owncast')
                <iframe src="{{ rtrim($owncastUrl, '/') }}/embed/video" class="w-full h-full" frameborder="0" allowfullscreen></iframe>
            @else
                @if($streamType === 'hls')
                    <div class="w-full h-full bg-slate-900 flex flex-col items-center justify-center text-center p-4">
                        <svg class="w-12 h-12 text-orange-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-white text-sm font-semibold">Custom HLS Stream Active</p>
                        <p class="text-gray-400 text-xs mt-1">Live HLS player is running on the main website homepage.</p>
                        <a href="{{ route('home') }}" target="_blank" class="mt-3 text-xs bg-orange-600 text-white px-4 py-2 rounded font-medium hover:bg-orange-500 transition-colors">
                            View Live Player on Site
                        </a>
                    </div>
                @else
                    <iframe src="{{ $streamEmbedUrl }}" class="w-full h-full" frameborder="0" allowfullscreen></iframe>
                @endif
            @endif
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const providerSelect = document.getElementById('stream_provider');
        const standardFields = document.getElementById('standard-stream-fields');
        const owncastFields = document.getElementById('owncast-stream-fields');

        function toggleFields() {
            const val = providerSelect.value;
            if (val === 'owncast') {
                standardFields.style.display = 'none';
                owncastFields.style.display = 'block';
            } else {
                standardFields.style.display = 'block';
                owncastFields.style.display = 'none';
            }
        }

        providerSelect.addEventListener('change', toggleFields);
        toggleFields(); // Initial run
    });
</script>
@endpush
