<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function edit()
    {
        $livestreamUrl  = Setting::get('livestream_url');
        $siteTitle      = Setting::get('site_title', '2026WORLDCUP.com.ng');
        $siteDesc       = Setting::get('site_description', 'Your home for live World Cup 2026 coverage and news from Nigeria.');
        $streamProvider = Setting::get('stream_provider', 'standard');
        $owncastUrl     = Setting::get('owncast_url');
        $owncastChat    = Setting::get('owncast_chat_enabled', '0');

        $streamData = $this->parseLivestreamUrl($livestreamUrl);

        return view('admin.settings', [
            'livestreamUrl'      => $livestreamUrl,
            'siteTitle'          => $siteTitle,
            'siteDesc'           => $siteDesc,
            'streamType'         => $streamData['type'],
            'streamEmbedUrl'     => $streamData['url'],
            'streamProvider'     => $streamProvider,
            'owncastUrl'         => $owncastUrl,
            'owncastChatEnabled' => $owncastChat,
        ]);
    }

    private function parseLivestreamUrl(?string $url): array
    {
        if (!$url) {
            return ['type' => null, 'url' => null];
        }

        // HLS
        if (preg_match('/\.m3u8(\?|$)/i', $url)) {
            return [
                'type' => 'hls',
                'url'  => $url
            ];
        }

        // YouTube
        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/|youtube\.com\/live\/)([a-zA-Z0-9_-]+)/i', $url, $matches)) {
            return [
                'type' => 'youtube',
                'url'  => "https://www.youtube.com/embed/" . $matches[1]
            ];
        }

        if (str_contains($url, 'youtube.com/embed/live_stream')) {
            return [
                'type' => 'youtube',
                'url'  => $url
            ];
        }

        return [
            'type' => 'iframe',
            'url'  => $url
        ];
    }

    public function update(Request $request)
    {
        $request->validate([
            'stream_provider'      => ['required', 'string', 'in:standard,owncast'],
            'livestream_url'       => ['nullable', 'url', 'max:2048'],
            'owncast_url'          => ['nullable', 'url', 'max:2048'],
            'owncast_chat_enabled' => ['nullable', 'boolean'],
            'site_title'           => ['nullable', 'string', 'max:100'],
            'site_description'     => ['nullable', 'string', 'max:255'],
        ]);

        Setting::set('stream_provider', $request->input('stream_provider', 'standard'));
        Setting::set('livestream_url', $request->input('livestream_url'));
        Setting::set('owncast_url', $request->input('owncast_url'));
        Setting::set('owncast_chat_enabled', $request->has('owncast_chat_enabled') ? '1' : '0');
        Setting::set('site_title', $request->input('site_title', '2026WORLDCUP.com.ng'));
        Setting::set('site_description', $request->input('site_description', 'Your home for live World Cup 2026 coverage.'));

        return redirect()->route('admin.settings')
            ->with('success', 'Settings updated successfully.');
    }
}
