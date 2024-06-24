@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('vendor/css/bootstrap-colorpicker.css') }}" />
<link rel="stylesheet" href="{{ asset('vendor/css/image-picker.min.css') }}">
@endpush
@section('content')

<div class="content-wrapper">
    @include($view)
</div>

@endsection
