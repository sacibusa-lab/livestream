<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Fixture;
use App\Models\Setting;
use Carbon\Carbon;

class PullFixturesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fixtures:pull';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically pull generated World Cup 2026 fixtures using OpenRouter API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $apiKey = Setting::get('openrouter_api_key') ?: config('services.openrouter.api_key');
        
        if (empty($apiKey)) {
            $this->error('OpenRouter API Key is not set. Please set it in the Admin Settings menu or .env file.');
            return Command::FAILURE;
        }

        $this->info('Contacting OpenRouter to generate World Cup 2026 fixtures...');

        $model = config('services.openrouter.model', 'meta-llama/llama-3-70b-instruct');
        
        // Detailed prompt to ensure JSON output
        $prompt = "You are a football data generator. Generate a JSON array of 104 realistic matches for the 2026 FIFA World Cup (including Group Stage and Knockouts). "
                . "The output MUST be valid, parsable JSON. Do not include markdown blocks or any other text before or after the JSON. "
                . "The JSON should be an array of objects, with exactly these keys: "
                . "'home_team' (string), 'home_team_code' (string, ISO 3166-1 alpha-2 code e.g., 'br', 'us', or 'gb-eng' for England), "
                . "'away_team' (string), 'away_team_code' (string, ISO 3166-1 alpha-2 code e.g., 'fr', 'mx'), "
                . "'group' (string e.g., 'Group A' or 'Knockout'), "
                . "'venue' (string e.g., 'Azteca Stadium'), 'match_time' (string in ISO 8601 format e.g., '2026-06-11T12:00:00Z'). "
                . "Make the teams realistic (e.g., Brazil, France, Argentina, USA, Mexico, Canada).";

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'HTTP-Referer' => config('app.url'),
                'X-Title' => config('app.name'),
            ])->timeout(300)->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => $model,
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ],
                'temperature' => 0.2, // Low temperature for consistent JSON
            ]);

            if ($response->failed()) {
                $this->error('Failed to connect to OpenRouter: ' . $response->body());
                return Command::FAILURE;
            }

            $jsonString = $response->json('choices.0.message.content');
            
            // Cleanup the response if it has markdown block wrappers
            $jsonString = trim($jsonString);
            if (str_starts_with($jsonString, '```json')) {
                $jsonString = substr($jsonString, 7);
            }
            if (str_starts_with($jsonString, '```')) {
                $jsonString = substr($jsonString, 3);
            }
            if (str_ends_with($jsonString, '```')) {
                $jsonString = substr($jsonString, 0, -3);
            }
            
            $fixtures = json_decode(trim($jsonString), true);

            if (json_last_error() !== JSON_ERROR_NONE || !is_array($fixtures)) {
                $this->error('Failed to parse JSON response from OpenRouter.');
                $this->line('Raw response: ' . $jsonString);
                return Command::FAILURE;
            }

            $this->info(count($fixtures) . ' fixtures generated. Saving to database...');
            
            $count = 0;
            foreach ($fixtures as $item) {
                $homeCode = strtolower($item['home_team_code'] ?? '');
                $awayCode = strtolower($item['away_team_code'] ?? '');

                Fixture::create([
                    'home_team' => $item['home_team'],
                    'home_team_flag_url' => $homeCode ? "https://flagcdn.com/w40/{$homeCode}.png" : null,
                    'away_team' => $item['away_team'],
                    'away_team_flag_url' => $awayCode ? "https://flagcdn.com/w40/{$awayCode}.png" : null,
                    'match_time' => Carbon::parse($item['match_time'])->setTimezone(config('app.timezone')),
                    'stage' => 'Group Stage',
                    'group' => $item['group'] ?? null,
                    'venue' => $item['venue'] ?? null,
                    'status' => 'upcoming',
                    'stream_provider' => 'standard',
                ]);
                $count++;
            }

            $this->info("Successfully imported {$count} fixtures!");
            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Error occurred: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
