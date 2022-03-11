@extends('site')

@section('content')
    <ul>
        @foreach($records as $record)
            <li><a href="/analize-medicale-explicate/{{ $record->slug }}.html" title="{{ $record->title }}">{{ $record->title }}</a></li>
        @endforeach
    </ul>
@endsection
