<?php

namespace App\Services;

use App\Models\Fixture;

class StandingsService
{
    /**
     * Calculate group standings based on fixture results.
     * Returns an array keyed by group name.
     */
    public function calculateGroupStandings()
    {
        $fixtures = Fixture::whereNotNull('group')
            ->where('group', '!=', '')
            ->where('stage', 'like', '%Group%')
            ->get();

        $groups = [];

        foreach ($fixtures as $fixture) {
            $groupName = $fixture->group;
            if (!isset($groups[$groupName])) {
                $groups[$groupName] = [];
            }

            // Initialize teams if not exist
            foreach ([$fixture->home_team => $fixture->home_team_flag_url, $fixture->away_team => $fixture->away_team_flag_url] as $team => $flag) {
                if (!isset($groups[$groupName][$team])) {
                    $groups[$groupName][$team] = [
                        'team' => $team,
                        'flag' => $flag,
                        'played' => 0,
                        'won' => 0,
                        'drawn' => 0,
                        'lost' => 0,
                        'gf' => 0,
                        'ga' => 0,
                        'gd' => 0,
                        'points' => 0,
                    ];
                }
            }

            // Process score if finished
            if ($fixture->status === 'finished' && $fixture->home_score !== null && $fixture->away_score !== null) {
                $h = $fixture->home_team;
                $a = $fixture->away_team;
                $hs = $fixture->home_score;
                $as = $fixture->away_score;

                $groups[$groupName][$h]['played']++;
                $groups[$groupName][$a]['played']++;

                $groups[$groupName][$h]['gf'] += $hs;
                $groups[$groupName][$h]['ga'] += $as;
                $groups[$groupName][$a]['gf'] += $as;
                $groups[$groupName][$a]['ga'] += $hs;

                if ($hs > $as) {
                    // Home win
                    $groups[$groupName][$h]['won']++;
                    $groups[$groupName][$h]['points'] += 3;
                    $groups[$groupName][$a]['lost']++;
                } elseif ($hs < $as) {
                    // Away win
                    $groups[$groupName][$a]['won']++;
                    $groups[$groupName][$a]['points'] += 3;
                    $groups[$groupName][$h]['lost']++;
                } else {
                    // Draw
                    $groups[$groupName][$h]['drawn']++;
                    $groups[$groupName][$h]['points'] += 1;
                    $groups[$groupName][$a]['drawn']++;
                    $groups[$groupName][$a]['points'] += 1;
                }
            }
        }

        // Sort standings
        foreach ($groups as $groupName => &$teams) {
            // Calculate GD
            foreach ($teams as &$team) {
                $team['gd'] = $team['gf'] - $team['ga'];
            }

            // Sort by Points DESC, then GD DESC, then GF DESC
            usort($teams, function ($a, $b) {
                if ($a['points'] !== $b['points']) return $b['points'] <=> $a['points'];
                if ($a['gd'] !== $b['gd']) return $b['gd'] <=> $a['gd'];
                return $b['gf'] <=> $a['gf'];
            });
        }

        ksort($groups); // Sort groups alphabetically
        return $groups;
    }

    /**
     * Get knockout bracket matches grouped by stage
     */
    public function getKnockoutBracket()
    {
        $fixtures = Fixture::where('stage', 'not like', '%Group%')
            ->orderBy('match_time', 'asc')
            ->get();

        $bracket = [];
        foreach ($fixtures as $fixture) {
            $stage = $fixture->stage;
            if (!isset($bracket[$stage])) {
                $bracket[$stage] = [];
            }
            $bracket[$stage][] = $fixture;
        }

        return $bracket;
    }
}
