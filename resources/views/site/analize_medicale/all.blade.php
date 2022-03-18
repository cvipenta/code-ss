@extends('site')

@section('content')
    <ul>
        @foreach($records as $record)
            <li><a href="/analize-medicale-explicate/{{ str_replace(' ', '-', $record->am_title) }}.html" title="{{ $record->am_title }}">{{ $record->am_title }}</a></li>
        @endforeach
    </ul>
@endsection
