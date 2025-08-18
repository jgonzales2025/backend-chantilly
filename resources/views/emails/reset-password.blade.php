@component('mail::layout')
{{-- Header --}}
@slot('header')
{{-- Eliminamos el header predeterminado --}}
@endslot

{{-- Body --}}
<div style="text-align: center;">
    <img src="{{ asset('storage/logo/logocheckout.png') }}" alt="Logo de La Casa del Chantilly" style="width: 320px; height: auto; max-width: 100%; margin-bottom: 20px;">
</div>

# Hola {{ $name }}

Haz clic en el botón de abajo para restablecer tu contraseña.

@component('mail::button', ['url' => $url])
Restablecer contraseña
@endcomponent

Si no solicitaste este cambio, puedes ignorar este mensaje.

Si tienes problemas al hacer clic en el botón, copia y pega esta URL en tu navegador:

{{ $url }}

Saludos,<br>
**La Casa del Chantilly**

{{-- Footer --}}
@slot('footer')
© {{ date('Y') }} La Casa del Chantilly. Todos los derechos reservados.
@endslot
@endcomponent
