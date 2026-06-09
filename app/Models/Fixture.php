<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fixture extends Model
{
    protected $fillable = [
        'home_team',
        'home_team_flag_url',
        'away_team',
        'away_team_flag_url',
        'match_time',
        'status',
        'home_score',
        'away_score',
        'stage',
        'group',
        'venue',
        'stream_provider',
        'owncast_url',
        'owncast_chat_enabled',
        'preview_content',
        'recap_content',
    ];

    protected $casts = [
        'match_time' => 'datetime',
        'owncast_chat_enabled' => 'boolean',
    ];
}
