@extends('backstage.templates.backstage')

@section('tools')
    <a href="{{ route('backstage.prizes.create') }}" class="button-create">Create prize</a>
@endsection

@section('content')
    <div id="card" class="bg-white shadow-lg mx-auto rounded-b-lg">
        <div class="px-10 pt-4 pb-8">
            @livewire('backstage.prize-table')
        </div>
    </div>
@endsection
