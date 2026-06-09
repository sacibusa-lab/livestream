@extends('layouts.admin')

@section('title', 'Edit Fixture')
@section('page_title', 'Edit Fixture')

@section('content')
<div class="max-w-4xl space-y-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sm:p-8">
        @if($errors->any())
            <div class="mb-5 bg-red-50 border border-red-200 rounded-lg p-4">
                @foreach($errors->all() as $error)
                    <p class="text-red-600 text-sm">• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="mb-6 flex items-center justify-between bg-purple-50 p-4 rounded-lg border border-purple-100">
            <div>
                <h3 class="text-purple-800 font-semibold">AI Content Generation</h3>
                <p class="text-purple-600 text-sm">Automatically write SEO-optimized previews or recaps.</p>
            </div>
            <div class="flex gap-2">
                <form action="{{ route('admin.fixtures.generate-content', $fixture->id) }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="type" value="preview">
                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1.5 rounded text-sm font-medium transition-colors" onclick="return confirm('This will overwrite any existing preview content. Continue?')">
                        Generate Preview
                    </button>
                </form>
                <form action="{{ route('admin.fixtures.generate-content', $fixture->id) }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="type" value="recap">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded text-sm font-medium transition-colors" onclick="return confirm('This will overwrite any existing recap content. Continue?')">
                        Generate Recap
                    </button>
                </form>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.fixtures.update', $fixture->id) }}">
            @method('PUT')
            @include('admin.fixtures.form')
        </form>
    </div>
</div>
@endsection
