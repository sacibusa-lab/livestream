@extends('layouts.admin')

@section('title', 'Manage Fixture Scores')

@section('content')
<div class="mb-8 flex justify-between items-center">
    <h1 class="text-3xl font-bold text-gray-900">Update Match Scores</h1>
    <p class="text-gray-500">Updating scores will automatically recalculate standings and progress teams.</p>
</div>

@if(session('success'))
    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 border-b">
                <th class="p-4 font-semibold text-gray-600 text-sm">Date</th>
                <th class="p-4 font-semibold text-gray-600 text-sm">Match</th>
                <th class="p-4 font-semibold text-gray-600 text-sm text-center">Score</th>
                <th class="p-4 font-semibold text-gray-600 text-sm">Stage</th>
                <th class="p-4 font-semibold text-gray-600 text-sm">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($fixtures as $fixture)
            <tr class="border-b hover:bg-gray-50">
                <td class="p-4 text-sm text-gray-600 whitespace-nowrap">
                    {{ \Carbon\Carbon::parse($fixture->match_time)->format('M d, Y h:i A') }}
                </td>
                <td class="p-4">
                    <div class="flex items-center gap-2 font-medium text-gray-900">
                        @if($fixture->home_team_flag_url)
                            <img src="{{ $fixture->home_team_flag_url }}" class="w-5 h-5 rounded-full object-cover">
                        @endif
                        {{ $fixture->home_team }} 
                        <span class="text-gray-400 mx-2">vs</span>
                        @if($fixture->away_team_flag_url)
                            <img src="{{ $fixture->away_team_flag_url }}" class="w-5 h-5 rounded-full object-cover">
                        @endif
                        {{ $fixture->away_team }}
                    </div>
                </td>
                
                <td class="p-4">
                    <form action="{{ route('admin.fixtures.updateScore', $fixture->id) }}" method="POST" class="flex items-center justify-center gap-2">
                        @csrf
                        @method('PUT')
                        <input type="number" name="home_score" value="{{ $fixture->home_score }}" class="w-16 p-2 border rounded text-center font-bold" min="0">
                        <span class="text-gray-500">-</span>
                        <input type="number" name="away_score" value="{{ $fixture->away_score }}" class="w-16 p-2 border rounded text-center font-bold" min="0">
                </td>
                <td class="p-4">
                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full font-medium">{{ $fixture->stage }}</span>
                </td>
                <td class="p-4">
                        <input type="hidden" name="status" value="finished">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded font-medium text-sm transition">
                            Save
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
