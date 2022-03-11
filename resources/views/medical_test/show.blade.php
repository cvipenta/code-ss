@extends('site')

@section('content')
    <h3>{{ $record->title }}</h3>
    <h4>{{ $record->category->slug }}</h4>
    <p>
        {!! $record->description !!}
    </p>
@endsection
