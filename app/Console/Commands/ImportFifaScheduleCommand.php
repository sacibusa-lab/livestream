<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Fixture;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;

class ImportFifaScheduleCommand extends Command
{
    protected $signature = 'fixtures:import-official';
    protected $description = 'Import the official 104 match FIFA World Cup schedule with team placeholders.';

    public function handle()
    {
        $this->info("Importing Official 2026 World Cup Schedule (104 Matches)...");

        $apiKey = Setting::get('openrouter_api_key');
        if (!$apiKey) {
            $this->error("OpenRouter API key is missing. Set it in the settings.");
            return 1;
        }

        $this->info("Deleting old fixtures...");
        Fixture::truncate();

        $prompt = "You are an official FIFA data generator. Generate a JSON array of exactly 104 matches for the 2026 FIFA World Cup (Group Stage, Round of 32, Round of 16, Quarter-Finals, Semi-Finals, Third Place, Final).\n"
                . "IMPORTANT RULES:\n"
                . "1. DO NOT use real countries. The teams are not qualified yet. You MUST use the official FIFA placeholders (e.g., 'Team A1', 'Team A2', 'Winner Match 73', 'Runner-up Group C').\n"
                . "2. Dates must be between June 11, 2026 and July 19, 2026.\n"
                . "3. Return ONLY valid JSON array. No markdown, no intro.\n"
                . "4. Array structure per object:\n"
                . "   - home_team: string (e.g. 'Team A1')\n"
                . "   - away_team: string (e.g. 'Team A2')\n"
                . "   - home_team_flag_url: null\n"
                . "   - away_team_flag_url: null\n"
                . "   - match_time: string (YYYY-MM-DD HH:MM:00)\n"
                . "   - status: 'upcoming'\n"
                . "   - home_score: null\n"
                . "   - away_score: null\n"
                . "   - stage: string (e.g. 'Group Stage', 'Round of 32', etc)\n"
                . "   - group: string (e.g. 'Group A', 'Group B', or null for knockouts)\n"
                . "   - venue: string (e.g. 'Estadio Azteca, Mexico City', 'MetLife Stadium, New York')\n";

        $this->info("Contacting OpenRouter AI (Timeout: 5 minutes)...");

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'HTTP-Referer' => url('/'),
                'X-Title' => 'WorldCup CMS',
            ])
            ->timeout(300)
            ->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => 'openai/gpt-4o',
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ],
                'temperature' => 0.1,
            ]);

            if ($response->failed()) {
                $this->error("OpenRouter Error: " . $response->body());
                return 1;
            }

            $data = $response->json();
            $content = $data['choices'][0]['message']['content'] ?? '';

            $content = preg_replace('/```json\s*/', '', $content);
            $content = preg_replace('/```/', '', $content);
            $content = trim($content);

            $fixtures = json_decode($content, true);

            if (!is_array($fixtures)) {
                $this->error("Failed to parse JSON. Raw output:");
                $this->line($content);
                return 1;
            }

            $this->info("Generated " . count($fixtures) . " matches. Saving to database...");

            foreach ($fixtures as $fixtureData) {
                Fixture::create($fixtureData);
            }

            $this->info("Success! Imported " . count($fixtures) . " official matches into the database.");
            return 0;

        } catch (\Exception $e) {
            $this->error("Exception occurred: " . $e->getMessage());
            return 1;
        }
    }
}
