<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fixture;
use Illuminate\Http\Request;
use App\Services\TournamentProgressionService;

class FixtureController extends Controller
{
    public function index()
    {
        $fixtures = Fixture::orderBy('match_time', 'asc')->get();
        return view('admin.fixtures.index', compact('fixtures'));
    }

    public function updateScore(Request $request, $id)
    {
        $request->validate([
            'home_score' => 'nullable|integer|min:0',
            'away_score' => 'nullable|integer|min:0',
            'status' => 'required|in:upcoming,live,finished'
        ]);

        $fixture = Fixture::findOrFail($id);
        $fixture->update([
            'home_score' => $request->home_score,
            'away_score' => $request->away_score,
            'status' => $request->status
        ]);

        // If a match is finished and has scores, evaluate progressions
        if ($fixture->status === 'finished' && $fixture->home_score !== null && $fixture->away_score !== null) {
            $progressionService = new TournamentProgressionService();
            $progressionService->processAdvancements();
        }

        return back()->with('success', 'Fixture updated successfully.');
    }
}
