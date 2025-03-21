@extends('backstage.templates.backstage')

@section('tools')
    <a href="{{ route('backstage.campaigns.index') }}" class="button-default">Campaigns</a>
@endsection

@section('content')
    <div id="card" class="bg-white shadow-lg mx-auto rounded-b-lg">
        <div class="px-10 pt-4 pb-8">
            <h1>Create a new campaign</h1>
            <form method="POST" action="{{ route('backstage.campaigns.store') }}">
                @include('backstage.campaigns.form', ['withTemplates' => true])
            </form>
        </div>
    </div>
@endsection
