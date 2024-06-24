@component('mail::message')
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level == 'error')
# Whoops!
@else
# Hello!
@endif
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
{{ $line }}

@endforeach
{!! $revertMailData!!}

<!-- @component('mail::button', ['url' => $pdfPath, 'download' => ''])
View PDF
@endcomponent -->

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach


@endcomponent
