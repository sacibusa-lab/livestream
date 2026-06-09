<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Setting;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        $livestreamUrl  = Setting::get('livestream_url');
        $siteTitle      = Setting::get('site_title', '2026WORLDCUP.com.ng');
        $streamProvider = Setting::get('stream_provider', 'standard');
        $owncastUrl     = Setting::get('owncast_url');
        $owncastChat    = Setting::get('owncast_chat_enabled', '0');

        $streamData = $this->parseLivestreamUrl($livestreamUrl);

        $posts = BlogPost::published()
            ->orderBy('published_at', 'desc')
            ->paginate(10);

        return view('home', [
            'livestreamUrl'      => $livestreamUrl,
            'streamType'         => $streamData['type'],
            'streamEmbedUrl'     => $streamData['url'],
            'streamProvider'     => $streamProvider,
            'owncastUrl'         => $owncastUrl,
            'owncastChatEnabled' => $owncastChat === '1' || $owncastChat === 1 || $owncastChat === true,
            'posts'              => $posts,
            'siteTitle'          => $siteTitle
        ]);
    }

    private function parseLivestreamUrl(?string $url): array
    {
        if (!$url) {
            return ['type' => null, 'url' => null];
        }

        // Detect HLS (.m3u8) streams
        if (preg_match('/\.m3u8(\?|$)/i', $url)) {
            return [
                'type' => 'hls',
                'url'  => $url
            ];
        }

        // Detect YouTube Video (watch, share, live, embed)
        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/|youtube\.com\/live\/)([a-zA-Z0-9_-]+)/i', $url, $matches)) {
            return [
                'type' => 'youtube',
                'url'  => "https://www.youtube.com/embed/" . $matches[1]
            ];
        }

        // Detect YouTube Channel Live embed (e.g. embed/live_stream?channel=...)
        if (str_contains($url, 'youtube.com/embed/live_stream')) {
            return [
                'type' => 'youtube',
                'url'  => $url
            ];
        }

        // Default/Fallback: treat as raw iframe
        return [
            'type' => 'iframe',
            'url'  => $url
        ];
    }

    public function showPost(string $slug)
    {
        $post = BlogPost::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        return view('post', compact('post'));
    }
}
