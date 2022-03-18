@extends('site')

@section('content')
    <div class="container mt-5">
        <ul>
            @foreach($records as $record)
                <li><a href="/analize-medicale-explicate/{{ $record->slug }}.html"
                       title="{{ $record->title }}">{{ $record->title }}</a>
                </li>
            @endforeach
        </ul>

        {{-- Pagination --}}
        <div class="d-flex justify-content-center">
            {!! $records->links() !!}
        </div>
    </div>
@endsection
