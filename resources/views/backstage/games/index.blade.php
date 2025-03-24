@extends('backstage.templates.backstage')

@section('content')
    <div id="card" class="bg-white shadow-lg mx-auto rounded-b-lg">
        <div class="px-10 pt-4 pb-8">
            @livewire('backstage.game-table')
        </div>
    </div>
@endsection
