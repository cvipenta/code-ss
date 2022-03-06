@extends('site')

@section('content')
    <ul>
        @foreach($categories as $category)
            <li>
                <a href="/analize-medicale/{{ \Illuminate\Support\Str::slug($category) ?: 'na' }}.html"
                   title="{{ $category ?? 'Fara categorie' }}"
                >{{ $category ?? 'Fara categorie' }}</a>
            </li>
        @endforeach
    </ul>
@endsection

