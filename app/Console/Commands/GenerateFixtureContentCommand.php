<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Fixture;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;

class GenerateFixtureContentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fixtures:generate-content {fixture} {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate AI preview or recap for a fixture';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $fixtureId = $this->argument('fixture');
        $type = $this->argument('type'); // 'preview' or 'recap'

        $fixture = Fixture::find($fixtureId);
        
        if (!$fixture) {
            $this->error('Fixture not found.');
            return Command::FAILURE;
        }

        $apiKey = Setting::get('openrouter_api_key') ?: config('services.openrouter.api_key');
        if (empty($apiKey)) {
            $this->error('OpenRouter API Key is not set.');
            return Command::FAILURE;
        }

        $model = config('services.openrouter.model', 'meta-llama/llama-3-70b-instruct');
        
        $prompt = "";
        if ($type === 'preview') {
            $prompt = "You are an expert football pundit writing for a premium World Cup 2026 website. "
                    . "Write an exciting, SEO-optimized match preview for the upcoming Group Stage match between "
                    . "{$fixture->home_team} and {$fixture->away_team}. "
                    . "The match will be played at {$fixture->venue}. "
                    . "Discuss key players, tactical matchups, and historical context. "
                    . "Output ONLY valid Markdown. Use headings, bullet points, and bold text for emphasis.";
        } else if ($type === 'recap') {
            $score = "{$fixture->home_score} - {$fixture->away_score}";
            $prompt = "You are an expert football journalist writing for a premium World Cup 2026 website. "
                    . "Write a dramatic, detailed match recap for the game between "
                    . "{$fixture->home_team} and {$fixture->away_team}. "
                    . "The final score was {$fixture->home_team} {$fixture->home_score} - {$fixture->away_score} {$fixture->away_team}. "
                    . "Invent realistic key moments (goals, saves, cards) that fit this final score. "
                    . "Output ONLY valid Markdown. Use headings, bullet points, and bold text for emphasis.";
        } else {
            $this->error('Invalid type. Must be preview or recap.');
            return Command::FAILURE;
        }

        $this->info("Generating {$type} for {$fixture->home_team} vs {$fixture->away_team}...");

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'HTTP-Referer' => config('app.url'),
                'X-Title' => config('app.name'),
            ])->timeout(120)->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => $model,
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ],
                'temperature' => 0.7,
            ]);

            if ($response->failed()) {
                $this->error('Failed to connect to OpenRouter: ' . $response->body());
                return Command::FAILURE;
            }

            $markdown = $response->json('choices.0.message.content');
            
            if ($type === 'preview') {
                $fixture->update(['preview_content' => trim($markdown)]);
            } else {
                $fixture->update(['recap_content' => trim($markdown)]);
            }

            $this->info("Successfully generated and saved {$type}!");
            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Error occurred: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
