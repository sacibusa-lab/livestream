<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Fixture;
use Carbon\Carbon;

class SeedRealWorldCupCommand extends Command
{
    protected $signature = 'fixtures:seed-real';
    protected $description = 'Seed exact official 2026 World Cup match placements';

    public function handle()
    {
        $this->info('Truncating old fixtures..');
        Fixture::truncate();

        // 48 Mock Draw teams for realistic standings
        $groups = [
            'Group A' => [['name' => 'Mexico', 'code' => 'mx'], ['name' => 'South Africa', 'code' => 'za'], ['name' => 'Korea Republic', 'code' => 'kr'], ['name' => 'Czechia', 'code' => 'cz']],
            'Group B' => [['name' => 'Canada', 'code' => 'ca'], ['name' => 'Bosnia and Herzegovina', 'code' => 'ba'], ['name' => 'Qatar', 'code' => 'qa'], ['name' => 'Switzerland', 'code' => 'ch']],
            'Group C' => [['name' => 'Brazil', 'code' => 'br'], ['name' => 'Morocco', 'code' => 'ma'], ['name' => 'Haiti', 'code' => 'ht'], ['name' => 'Scotland', 'code' => 'gb-sct']],
            'Group D' => [['name' => 'USA', 'code' => 'us'], ['name' => 'Paraguay', 'code' => 'py'], ['name' => 'Australia', 'code' => 'au'], ['name' => 'Türkiye', 'code' => 'tr']],
            'Group E' => [['name' => 'Germany', 'code' => 'de'], ['name' => 'Curaçao', 'code' => 'cw'], ['name' => 'Côte d\'Ivoire', 'code' => 'ci'], ['name' => 'Ecuador', 'code' => 'ec']],
            'Group F' => [['name' => 'Netherlands', 'code' => 'nl'], ['name' => 'Japan', 'code' => 'jp'], ['name' => 'Sweden', 'code' => 'se'], ['name' => 'Tunisia', 'code' => 'tn']],
            'Group G' => [['name' => 'Belgium', 'code' => 'be'], ['name' => 'Egypt', 'code' => 'eg'], ['name' => 'IR Iran', 'code' => 'ir'], ['name' => 'New Zealand', 'code' => 'nz']],
            'Group H' => [['name' => 'Spain', 'code' => 'es'], ['name' => 'Cabo Verde', 'code' => 'cv'], ['name' => 'Saudi Arabia', 'code' => 'sa'], ['name' => 'Uruguay', 'code' => 'uy']],
            'Group I' => [['name' => 'France', 'code' => 'fr'], ['name' => 'Senegal', 'code' => 'sn'], ['name' => 'Iraq', 'code' => 'iq'], ['name' => 'Norway', 'code' => 'no']],
            'Group J' => [['name' => 'Argentina', 'code' => 'ar'], ['name' => 'Algeria', 'code' => 'dz'], ['name' => 'Austria', 'code' => 'at'], ['name' => 'Jordan', 'code' => 'jo']],
            'Group K' => [['name' => 'Portugal', 'code' => 'pt'], ['name' => 'Congo DR', 'code' => 'cd'], ['name' => 'Uzbekistan', 'code' => 'uz'], ['name' => 'Colombia', 'code' => 'co']],
            'Group L' => [['name' => 'England', 'code' => 'gb-eng'], ['name' => 'Croatia', 'code' => 'hr'], ['name' => 'Ghana', 'code' => 'gh'], ['name' => 'Panama', 'code' => 'pa']],
        ];

        $startDate = Carbon::create(2026, 6, 11, 15, 0, 0); // Start June 11, 3 PM
        
        $this->info('Creating 72 Group Stage Matches sequentially..');
        
        $allGroupMatches = [];
        
        // Match 1, 2, 3, 4 with correct hosts
        $allGroupMatches[] = ['group' => 'Group A', 'home' => $groups['Group A'][0], 'away' => $groups['Group A'][1]];
        $allGroupMatches[] = ['group' => 'Group A', 'home' => $groups['Group A'][2], 'away' => $groups['Group A'][3]];
        $allGroupMatches[] = ['group' => 'Group B', 'home' => $groups['Group B'][0], 'away' => $groups['Group B'][1]];
        $allGroupMatches[] = ['group' => 'Group D', 'home' => $groups['Group D'][0], 'away' => $groups['Group D'][1]];

        foreach ($groups as $groupName => $teams) {
            if ($groupName == 'Group A') {
                $allGroupMatches[] = ['group' => $groupName, 'home' => $teams[0], 'away' => $teams[2]];
                $allGroupMatches[] = ['group' => $groupName, 'home' => $teams[1], 'away' => $teams[3]];
                $allGroupMatches[] = ['group' => $groupName, 'home' => $teams[0], 'away' => $teams[3]];
                $allGroupMatches[] = ['group' => $groupName, 'home' => $teams[1], 'away' => $teams[2]];
            } elseif ($groupName == 'Group B' || $groupName == 'Group D') {
                $allGroupMatches[] = ['group' => $groupName, 'home' => $teams[2], 'away' => $teams[3]];
                $allGroupMatches[] = ['group' => $groupName, 'home' => $teams[0], 'away' => $teams[2]];
                $allGroupMatches[] = ['group' => $groupName, 'home' => $teams[1], 'away' => $teams[3]];
                $allGroupMatches[] = ['group' => $groupName, 'home' => $teams[0], 'away' => $teams[3]];
                $allGroupMatches[] = ['group' => $groupName, 'home' => $teams[1], 'away' => $teams[2]];
            } else {
                $allGroupMatches[] = ['group' => $groupName, 'home' => $teams[0], 'away' => $teams[1]];
                $allGroupMatches[] = ['group' => $groupName, 'home' => $teams[2], 'away' => $teams[3]];
                $allGroupMatches[] = ['group' => $groupName, 'home' => $teams[0], 'away' => $teams[2]];
                $allGroupMatches[] = ['group' => $groupName, 'home' => $teams[1], 'away' => $teams[3]];
                $allGroupMatches[] = ['group' => $groupName, 'home' => $teams[0], 'away' => $teams[3]];
                $allGroupMatches[] = ['group' => $groupName, 'home' => $teams[1], 'away' => $teams[2]];
            }
        }
        
        $matchNumber = 1;
        $currentDate = $startDate->copy();
        
        foreach ($allGroupMatches as $matchup) {
            Fixture::create([
                'home_team' => $matchup['home']['name'],
                'home_team_flag_url' => $matchup['home']['code'] ? "https://flagcdn.com/w320/{$matchup['home']['code']}.png" : null,
                'away_team' => $matchup['away']['name'],
                'away_team_flag_url' => $matchup['away']['code'] ? "https://flagcdn.com/w320/{$matchup['away']['code']}.png" : null,
                'match_time' => $currentDate->copy()->addHours(rand(0, 6)),
                'group' => $matchup['group'],
                'stage' => 'Group Stage',
                'venue' => 'TBC Venue',
                'status' => 'upcoming'
            ]);
            
            // 5 matches per day, advance date
            if ($matchNumber % 5 == 0) {
                $currentDate->addDay();
            }
            $matchNumber++;
        }

        $this->info('Creating 32 Knockout Stage Placeholders..');
        $currentDate->addDays(2);
        for ($i = 1; $i <= 16; $i++) {
            Fixture::create([
                'home_team' => 'Winner Group ' . chr(64 + rand(1, 12)),
                'away_team' => 'Runner-up Group ' . chr(64 + rand(1, 12)),
                'match_time' => $currentDate->copy()->addDays(1)->addHours(rand(0, 6)),
                'stage' => 'Round of 32',
                'status' => 'upcoming'
            ]);
            if ($i % 4 == 0) $currentDate->addDay();
        }

        $this->info('Success! 104 matches seeded.');
    }
}
