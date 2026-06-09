<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Fixture;
use App\Services\TournamentProgressionService;

class SimulateGroupStageCommand extends Command
{
    protected $signature = 'fixtures:simulate-groups';
    protected $description = 'Simulates random scores for all group stage matches and processes advancements.';

    public function handle()
    {
        $this->info("Simulating group stage matches...");

        $fixtures = Fixture::where('stage', 'like', '%Group%')->get();

        foreach ($fixtures as $fixture) {
            $fixture->update([
                'home_score' => rand(0, 4),
                'away_score' => rand(0, 4),
                'status' => 'finished'
            ]);
        }

        $this->info("Scores generated. Processing tournament advancements...");

        $progressionService = new TournamentProgressionService();
        $progressionService->processAdvancements();

        $this->info("Done! Check out the updated standings and brackets.");
    }
}
