@extends('site')

@section('content')
    <h1>{{ $record->title }}</h1>
    <p>
        {!! $record->description !!}
    </p>
@endsection
