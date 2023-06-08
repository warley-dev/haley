<h1>@yield('h1') @csrf</h1>

@php
    echo 'aaaaa <br>';
@endphp

@if($url = request()->url())
    <h1>{{ $url }}</h1>
@elseif(false)

@elseif(true)

@else
    <h1>else</h1>
@endif

@foreach($filmes as $value)
    <p>{{ $value->titulo }}</p>
@endforeach