@extends('layouts.app')

@section('title', 'World Cup 2026 Standings & Bracket')
@section('meta_description', 'View the latest World Cup 2026 Group Stage standings and the Knockout Stage bracket.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="text-center mb-12">
        <h1 class="font-display text-4xl md:text-5xl font-bold mb-4 text-white">
            Tournament <span class="text-transparent bg-clip-text gradient-brand">Standings</span>
        </h1>
        <p class="text-gray-400 text-lg">Current group tables and knockout progressions.</p>
    </div>

    {{-- Group Stage Tables --}}
    @if(count($groups) > 0)
        <div class="mb-16">
            <h2 class="text-2xl font-display font-bold text-white mb-6 border-b border-white/10 pb-3">Group Stage</h2>
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($groups as $groupName => $teams)
                    <div class="glass rounded-2xl overflow-hidden border border-white/10">
                        <div class="bg-gray-800/80 px-4 py-3 border-b border-white/5 flex items-center justify-between">
                            <h3 class="font-bold text-white tracking-wide uppercase">{{ $groupName }}</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="text-xs text-gray-500 uppercase bg-gray-900/50">
                                    <tr>
                                        <th scope="col" class="px-4 py-2 font-medium">Team</th>
                                        <th scope="col" class="px-2 py-2 font-medium text-center" title="Played">P</th>
                                        <th scope="col" class="px-2 py-2 font-medium text-center" title="Won">W</th>
                                        <th scope="col" class="px-2 py-2 font-medium text-center" title="Drawn">D</th>
                                        <th scope="col" class="px-2 py-2 font-medium text-center" title="Lost">L</th>
                                        <th scope="col" class="px-2 py-2 font-medium text-center" title="Goal Difference">GD</th>
                                        <th scope="col" class="px-4 py-2 font-bold text-center text-brand-400" title="Points">Pts</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5">
                                    @foreach($teams as $index => $team)
                                        <tr class="hover:bg-white/5 transition-colors {{ $index < 2 ? 'bg-green-900/10' : '' }}">
                                            <td class="px-4 py-3 flex items-center gap-2">
                                                <span class="text-xs text-gray-500 font-mono w-3">{{ $index + 1 }}</span>
                                                @if($team['flag'])
                                                    <img src="{{ $team['flag'] }}" alt="flag" class="w-5 h-3.5 object-cover rounded shadow-sm">
                                                @else
                                                    <div class="w-5 h-3.5 bg-gray-700 rounded shadow-sm"></div>
                                                @endif
                                                <span class="font-semibold text-gray-200 line-clamp-1">{{ $team['team'] }}</span>
                                            </td>
                                            <td class="px-2 py-3 text-center text-gray-400">{{ $team['played'] }}</td>
                                            <td class="px-2 py-3 text-center text-gray-400">{{ $team['won'] }}</td>
                                            <td class="px-2 py-3 text-center text-gray-400">{{ $team['drawn'] }}</td>
                                            <td class="px-2 py-3 text-center text-gray-400">{{ $team['lost'] }}</td>
                                            <td class="px-2 py-3 text-center text-gray-400 font-mono">{{ $team['gd'] > 0 ? '+'.$team['gd'] : $team['gd'] }}</td>
                                            <td class="px-4 py-3 text-center font-bold text-white">{{ $team['points'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Knockout Bracket --}}
    @if(count($bracket) > 0)
        <div>
            <h2 class="text-2xl font-display font-bold text-white mb-6 border-b border-white/10 pb-3">Knockout Stage</h2>
            <div class="space-y-8">
                @foreach($bracket as $stageName => $stageFixtures)
                    <div>
                        <h3 class="text-lg font-bold text-brand-500 mb-4">{{ $stageName }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            @foreach($stageFixtures as $fixture)
                                <a href="{{ route('fixtures.show', $fixture->id) }}" class="block glass p-4 rounded-xl border border-white/10 card-hover bg-gray-900/50">
                                    <div class="flex flex-col gap-2">
                                        {{-- Home --}}
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                @if($fixture->home_team_flag_url)
                                                    <img src="{{ $fixture->home_team_flag_url }}" class="w-5 h-3.5 object-cover rounded">
                                                @endif
                                                <span class="text-sm font-semibold text-gray-200">{{ $fixture->home_team }}</span>
                                            </div>
                                            <span class="text-sm font-bold {{ $fixture->home_score > $fixture->away_score ? 'text-white' : 'text-gray-500' }}">
                                                {{ $fixture->home_score ?? '-' }}
                                            </span>
                                        </div>
                                        {{-- Away --}}
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                @if($fixture->away_team_flag_url)
                                                    <img src="{{ $fixture->away_team_flag_url }}" class="w-5 h-3.5 object-cover rounded">
                                                @endif
                                                <span class="text-sm font-semibold text-gray-200">{{ $fixture->away_team }}</span>
                                            </div>
                                            <span class="text-sm font-bold {{ $fixture->away_score > $fixture->home_score ? 'text-white' : 'text-gray-500' }}">
                                                {{ $fixture->away_score ?? '-' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mt-3 pt-3 border-t border-white/5 text-xs text-gray-500 text-center flex items-center justify-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        {{ $fixture->match_time->format('M d • H:i') }}
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if(count($groups) === 0 && count($bracket) === 0)
        <div class="py-20 text-center glass rounded-2xl">
            <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            <h3 class="text-xl font-semibold text-gray-300 mb-2">No Data Available</h3>
            <p class="text-gray-500">Standings will be generated once fixtures are added and matches are played.</p>
        </div>
    @endif
</div>
@endsection
