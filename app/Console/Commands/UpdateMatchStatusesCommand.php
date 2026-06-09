<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Fixture;
use Carbon\Carbon;

class UpdateMatchStatusesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fixtures:update-statuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically transition fixture statuses based on their match time.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        // Upcoming -> Live
        // A match becomes 'live' exactly at its match_time
        $wentLive = Fixture::where('status', 'upcoming')
            ->where('match_time', '<=', $now)
            ->update(['status' => 'live']);

        // Live -> Finished
        // Assume a match takes ~115 minutes (90m + 15m HT + 10m stoppage).
        // For knockouts, it could be longer (Extra time + Pens), but for automation, we'll use 130 mins to be safe.
        $finished = Fixture::where('status', 'live')
            ->where('match_time', '<=', $now->copy()->subMinutes(130))
            ->update(['status' => 'finished']);

        if ($wentLive > 0 || $finished > 0) {
            $this->info("Updated {$wentLive} matches to LIVE and {$finished} matches to FINISHED.");
        } else {
            $this->info("No match statuses needed updating.");
        }
    }
}
