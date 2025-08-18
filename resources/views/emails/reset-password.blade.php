@component('mail::message')
<img src="{{ asset('storage/logo/logocheckout.png') }}" alt="Logo de La Casa del Chantilly" style="width: 150px; margin-bottom: 20px;">

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
@endcomponent
