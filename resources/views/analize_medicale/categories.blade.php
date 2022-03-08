@extends('site')

@section('content')
    <ul>
        @foreach($categories as $category)
            <li>
                <a href="/analize-medicale/{{ $category['slug'] }}.html" title="{{ $category['title'] }}">{{ $category['title'] }}</a>
            </li>
        @endforeach
    </ul>
@endsection

