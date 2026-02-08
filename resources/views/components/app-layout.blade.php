@props(['header' => null])

@extends('layouts.app')

@section('content')
    @if ($header)
        <div class="mb-4">
            {{ $header }}
        </div>
    @endif
    {{ $slot }}
@endsection 