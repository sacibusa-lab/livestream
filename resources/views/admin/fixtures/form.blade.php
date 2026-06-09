@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    {{-- Home Team --}}
    <div class="space-y-4">
        <h3 class="text-sm font-semibold text-gray-900 border-b pb-2">Home Team</h3>
        <div>
            <label for="home_team" class="block text-sm font-semibold text-gray-700 mb-1.5">Team Name *</label>
            <input type="text" id="home_team" name="home_team" value="{{ old('home_team', $fixture->home_team ?? '') }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 outline-none">
        </div>
        <div>
            <label for="home_team_flag_url" class="block text-sm font-semibold text-gray-700 mb-1.5">Flag URL</label>
            <input type="url" id="home_team_flag_url" name="home_team_flag_url" value="{{ old('home_team_flag_url', $fixture->home_team_flag_url ?? '') }}" placeholder="https://flagcdn.com/w40/br.png" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 outline-none">
            <p class="text-xs text-gray-500 mt-1">Leave blank if no flag.</p>
        </div>
        <div>
            <label for="home_score" class="block text-sm font-semibold text-gray-700 mb-1.5">Score</label>
            <input type="number" id="home_score" name="home_score" value="{{ old('home_score', $fixture->home_score ?? '') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 outline-none">
        </div>
    </div>

    {{-- Away Team --}}
    <div class="space-y-4">
        <h3 class="text-sm font-semibold text-gray-900 border-b pb-2">Away Team</h3>
        <div>
            <label for="away_team" class="block text-sm font-semibold text-gray-700 mb-1.5">Team Name *</label>
            <input type="text" id="away_team" name="away_team" value="{{ old('away_team', $fixture->away_team ?? '') }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 outline-none">
        </div>
        <div>
            <label for="away_team_flag_url" class="block text-sm font-semibold text-gray-700 mb-1.5">Flag URL</label>
            <input type="url" id="away_team_flag_url" name="away_team_flag_url" value="{{ old('away_team_flag_url', $fixture->away_team_flag_url ?? '') }}" placeholder="https://flagcdn.com/w40/de.png" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 outline-none">
        </div>
        <div>
            <label for="away_score" class="block text-sm font-semibold text-gray-700 mb-1.5">Score</label>
            <input type="number" id="away_score" name="away_score" value="{{ old('away_score', $fixture->away_score ?? '') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 outline-none">
        </div>
    </div>
</div>

<hr class="my-6 border-gray-100">

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="space-y-4">
        <h3 class="text-sm font-semibold text-gray-900 border-b pb-2">Match Details</h3>
        <div>
            <label for="match_time" class="block text-sm font-semibold text-gray-700 mb-1.5">Match Time *</label>
            <input type="datetime-local" id="match_time" name="match_time" value="{{ old('match_time', isset($fixture) && $fixture->match_time ? $fixture->match_time->format('Y-m-d\TH:i') : '') }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 outline-none">
        </div>
        <div>
            <label for="status" class="block text-sm font-semibold text-gray-700 mb-1.5">Status *</label>
            <select id="status" name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 outline-none">
                <option value="upcoming" {{ old('status', $fixture->status ?? '') === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                <option value="live" {{ old('status', $fixture->status ?? '') === 'live' ? 'selected' : '' }}>Live</option>
                <option value="finished" {{ old('status', $fixture->status ?? '') === 'finished' ? 'selected' : '' }}>Finished</option>
            </select>
        </div>
        <div>
            <label for="stage" class="block text-sm font-semibold text-gray-700 mb-1.5">Stage</label>
            <input type="text" id="stage" name="stage" value="{{ old('stage', $fixture->stage ?? '') }}" placeholder="e.g. Group Stage, Round of 16" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 outline-none">
        </div>
        <div>
            <label for="group" class="block text-sm font-semibold text-gray-700 mb-1.5">Group</label>
            <input type="text" id="group" name="group" value="{{ old('group', $fixture->group ?? '') }}" placeholder="e.g. Group A" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 outline-none">
        </div>
        <div>
            <label for="venue" class="block text-sm font-semibold text-gray-700 mb-1.5">Venue</label>
            <input type="text" id="venue" name="venue" value="{{ old('venue', $fixture->venue ?? '') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 outline-none">
        </div>
    </div>

    <div class="space-y-4">
        <h3 class="text-sm font-semibold text-gray-900 border-b pb-2">Stream Overrides</h3>
        <p class="text-xs text-gray-500">Leave these blank to use the default global settings for streams.</p>
        
        <div>
            <label for="stream_provider" class="block text-sm font-semibold text-gray-700 mb-1.5">Stream Provider Override</label>
            <select id="stream_provider" name="stream_provider" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 outline-none">
                <option value="">-- Use Global Setting --</option>
                <option value="standard" {{ old('stream_provider', $fixture->stream_provider ?? '') === 'standard' ? 'selected' : '' }}>Standard Player (YouTube / HLS)</option>
                <option value="owncast" {{ old('stream_provider', $fixture->stream_provider ?? '') === 'owncast' ? 'selected' : '' }}>Owncast (Live Stream + Chat)</option>
            </select>
        </div>

        <div>
            <label for="owncast_url" class="block text-sm font-semibold text-gray-700 mb-1.5">Owncast URL Override</label>
            <input type="url" id="owncast_url" name="owncast_url" value="{{ old('owncast_url', $fixture->owncast_url ?? '') }}" placeholder="http://custom-stream.com" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 outline-none">
        </div>

        <div class="flex items-center">
            <input type="hidden" name="owncast_chat_enabled" value="0">
            <input type="checkbox" id="owncast_chat_enabled" name="owncast_chat_enabled" value="1" {{ old('owncast_chat_enabled', $fixture->owncast_chat_enabled ?? false) ? 'checked' : '' }} class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded">
            <label for="owncast_chat_enabled" class="ml-2 block text-sm text-gray-700 font-medium select-none">Enable Owncast Live Chat for this match</label>
        </div>
    </div>
</div>

<hr class="my-6 border-gray-100">

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="space-y-4">
        <h3 class="text-sm font-semibold text-purple-900 border-b pb-2">AI Match Preview</h3>
        <div>
            <label for="preview_content" class="block text-sm font-semibold text-gray-700 mb-1.5">Preview Content (Markdown)</label>
            <textarea id="preview_content" name="preview_content" rows="6" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-purple-500/30 focus:border-purple-500 outline-none">{{ old('preview_content', $fixture->preview_content ?? '') }}</textarea>
        </div>
    </div>
    <div class="space-y-4">
        <h3 class="text-sm font-semibold text-indigo-900 border-b pb-2">AI Match Recap</h3>
        <div>
            <label for="recap_content" class="block text-sm font-semibold text-gray-700 mb-1.5">Recap Content (Markdown)</label>
            <textarea id="recap_content" name="recap_content" rows="6" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 outline-none">{{ old('recap_content', $fixture->recap_content ?? '') }}</textarea>
        </div>
    </div>
</div>

<div class="mt-8 pt-5 border-t border-gray-100 flex items-center gap-3 justify-end">
    <a href="{{ route('admin.fixtures.index') }}" class="text-sm text-gray-500 hover:text-gray-700 transition-colors font-medium">Cancel</a>
    <button type="submit" class="inline-flex items-center gap-2 bg-orange-600 hover:bg-orange-500 text-white font-semibold px-6 py-2 rounded-lg text-sm transition-all shadow-sm">
        Save Fixture
    </button>
</div>
