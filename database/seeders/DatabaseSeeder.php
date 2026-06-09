<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create the one admin user
        Admin::updateOrCreate(
            ['email' => 'admin@2026worldcup.com.ng'],
            [
                'name'     => 'Admin User',
                'password' => Hash::make('password123'),
            ]
        );

        // Seed default settings
        $defaults = [
            'livestream_url'   => null,
            'site_title'       => '2026WORLDCUP.com.ng',
            'site_description' => 'Your #1 source for live World Cup 2026 coverage and news from Nigeria.',
            'stream_provider'  => 'standard',
            'owncast_url'      => null,
            'owncast_chat_enabled' => '0',
        ];

        foreach ($defaults as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        $this->command->info('✅ Admin seeded: admin@2026worldcup.com.ng / password123');
    }
}
