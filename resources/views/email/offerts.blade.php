@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => config('app.url')])
{{ config('app.name') }}
@endcomponent
@endslot


{{-- Body --}}

![logo]({{asset('img/products/1.jpg')}})

{{-- Subcopy --}}
@slot('subcopy')
@component('mail::subcopy')
{{ $data['body'] }}

@endcomponent
@endslot


{{-- Footer --}}
@slot('footer')
@component('mail::footer')
{{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.
@endcomponent
@endslot
@endcomponent