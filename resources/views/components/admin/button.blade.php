@props(['type' => 'blue'])


@if($attributes['action'])
    <a href="{!! $attributes['action'] !!}">
        <button
            class="bg-{{$type}}-500 hover:bg-{{$type}}-700 text-white font-semibold py-1 px-2 border border-{{$type}}-500 rounded">
            {{ $slot }}
        </button>
    </a>
@else
    <form method="POST" action="{!! $attributes['formaction'] !!}" style="display: inline-block">
        @csrf
        @method('DELETE')
        <button
            class="bg-{{$type}}-500 hover:bg-{{$type}}-700 text-white font-semibold py-1 px-2 border border-{{$type}}-500 rounded">
            {{ $slot }}
        </button>
    </form>
@endif

