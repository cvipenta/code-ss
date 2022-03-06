@extends('site')

@section('content')
    <h1>{{ $record->am_title }}</h1>
    <p>
        {!! $record->am_description !!}
    </p>
@endsection
