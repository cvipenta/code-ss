@extends('site')

@section('content')
    <ul>
        @foreach($records as $record)
            <li><a href="/analize-medicale-explicate/{{ $record->am_slug }}.html" title="{{ $record->am_title }}">{{ $record->am_title }}</a></li>
        @endforeach
    </ul>
@endsection
