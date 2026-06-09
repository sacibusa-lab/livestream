<?php
// Bootstrap Laravel
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Setting;

$apiKey = Setting::get('openrouter_api_key') ?: env('OPENROUTER_API_KEY');

if (!$apiKey) {
    echo "Error: OpenRouter API key not configured in settings or .env\n";
    exit(1);
}

$prompt = "Generate a JSON array of the official 104 matches of the 2026 FIFA World Cup. Return ONLY the raw JSON array. For each match object, include: \n'match_number' (1-104),\n'date' (e.g. '2026-06-11'),\n'group' ('Group A', 'Group B', etc. or 'Round of 32', etc.),\n'home_team' (e.g. 'Mexico' for match 1, 'Canada' for match 3, 'USA' for match 4, otherwise 'TBC'),\n'away_team' ('TBC'),\n'venue' (e.g. 'Estadio Azteca').\nOnly return the JSON. No markdown.";

$ch = curl_init("https://openrouter.ai/api/v1/chat/completions");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer " . $apiKey,
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    "model" => "openai/gpt-4o",
    "messages" => [["role" => "user", "content" => $prompt]]
]));
$response = curl_exec($ch);
$json = json_decode($response, true);
if (isset($json['choices'][0]['message']['content'])) {
    file_put_contents('schedule.json', $json['choices'][0]['message']['content']);
    echo "Done";
} else {
    echo "Failed to fetch response: " . json_encode($json);
}

