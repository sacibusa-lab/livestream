<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FixtureController extends Controller
{
    public function index()
    {
        $fixtures = \App\Models\Fixture::orderBy('match_time', 'asc')->get();
        return view('admin.fixtures.index', compact('fixtures'));
    }

    public function create()
    {
        return view('admin.fixtures.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'home_team' => 'required|string|max:255',
            'home_team_flag_url' => 'nullable|string|max:255',
            'away_team' => 'required|string|max:255',
            'away_team_flag_url' => 'nullable|string|max:255',
            'match_time' => 'required|date',
            'status' => 'required|in:upcoming,live,finished',
            'home_score' => 'nullable|integer',
            'away_score' => 'nullable|integer',
            'stage' => 'nullable|string|max:255',
            'group' => 'nullable|string|max:255',
            'venue' => 'nullable|string|max:255',
            'stream_provider' => 'nullable|string|in:standard,owncast',
            'owncast_url' => 'nullable|string|max:255',
            'owncast_chat_enabled' => 'boolean',
            'preview_content' => 'nullable|string',
            'recap_content' => 'nullable|string',
        ]);

        \App\Models\Fixture::create($validated);

        return redirect()->route('admin.fixtures.index')->with('success', 'Fixture created successfully.');
    }

    public function edit($id)
    {
        $fixture = \App\Models\Fixture::findOrFail($id);
        return view('admin.fixtures.edit', compact('fixture'));
    }

    public function update(Request $request, $id)
    {
        $fixture = \App\Models\Fixture::findOrFail($id);

        $validated = $request->validate([
            'home_team' => 'required|string|max:255',
            'home_team_flag_url' => 'nullable|string|max:255',
            'away_team' => 'required|string|max:255',
            'away_team_flag_url' => 'nullable|string|max:255',
            'match_time' => 'required|date',
            'status' => 'required|in:upcoming,live,finished',
            'home_score' => 'nullable|integer',
            'away_score' => 'nullable|integer',
            'stage' => 'nullable|string|max:255',
            'group' => 'nullable|string|max:255',
            'venue' => 'nullable|string|max:255',
            'stream_provider' => 'nullable|string|in:standard,owncast',
            'owncast_url' => 'nullable|string|max:255',
            'owncast_chat_enabled' => 'boolean',
            'preview_content' => 'nullable|string',
            'recap_content' => 'nullable|string',
        ]);

        $fixture->update($validated);

        // Check if status became finished to trigger tournament progression
        if ($fixture->status === 'finished') {
            $progressionService = new \App\Services\TournamentProgressionService();
            $progressionService->processAdvancements();
        }

        return redirect()->route('admin.fixtures.index')->with('success', 'Fixture updated successfully.');
    }

    public function destroy($id)
    {
        $fixture = \App\Models\Fixture::findOrFail($id);
        $fixture->delete();

        return redirect()->route('admin.fixtures.index')->with('success', 'Fixture deleted successfully.');
    }

    public function autoPull()
    {
        set_time_limit(300);
        
        try {
            $exitCode = \Illuminate\Support\Facades\Artisan::call('fixtures:pull');
            
            if ($exitCode === 0) {
                return redirect()->route('admin.fixtures.index')->with('success', 'Successfully pulled generated fixtures via OpenRouter!');
            } else {
                return redirect()->route('admin.fixtures.index')->with('error', 'Failed to pull fixtures. Check if OpenRouter API Key is set correctly.');
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.fixtures.index')->with('error', 'Error pulling fixtures: ' . $e->getMessage());
        }
    }

    public function clearAll()
    {
        \App\Models\Fixture::truncate();
        return redirect()->route('admin.fixtures.index')->with('success', 'All fixtures have been deleted. You can now start fresh.');
    }

    public function generateContent(Request $request, $id)
    {
        set_time_limit(150);
        
        $fixture = \App\Models\Fixture::findOrFail($id);
        $type = $request->input('type'); // 'preview' or 'recap'

        if (!in_array($type, ['preview', 'recap'])) {
            return redirect()->back()->with('error', 'Invalid content type requested.');
        }

        try {
            $exitCode = \Illuminate\Support\Facades\Artisan::call('fixtures:generate-content', [
                'fixture' => $fixture->id,
                'type' => $type
            ]);

            if ($exitCode === 0) {
                return redirect()->back()->with('success', ucfirst($type) . ' generated successfully!');
            } else {
                return redirect()->back()->with('error', 'Failed to generate ' . $type . '. Check if OpenRouter API Key is set correctly.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error generating content: ' . $e->getMessage());
        }
    }
}
