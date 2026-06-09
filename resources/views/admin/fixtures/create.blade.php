@extends('layouts.admin')

@section('title', 'Add New Fixture')
@section('page_title', 'Add New Fixture')

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

        <form method="POST" action="{{ route('admin.fixtures.store') }}">
            @include('admin.fixtures.form')
        </form>
    </div>
</div>
@endsection
