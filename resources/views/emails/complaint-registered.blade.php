<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>{{ $complaint->type_complaint === 'Reclamo' ? 'Reclamo' : 'Queja' }} Registrado</title>
</head>
<body style="background-color: #f6f6f6; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0;">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="background-color: #f6f6f6; width: 100%;">
        <tr>
            <td>&nbsp;</td>
            <td style="display: block; margin: 0 auto; max-width: 580px; padding: 10px; width: 580px;">
                <div style="box-sizing: border-box; display: block; margin: 0 auto; max-width: 580px; padding: 10px;">

                    <!-- START CENTERED WHITE CONTAINER -->
                    <table role="presentation" style="background: #ffffff; border-radius: 3px; width: 100%;">
                        
                        <!-- Logo -->
                        <tr>
                            <td style="padding: 35px 35px 20px 35px; text-align: center;">
                                <img src="{{ asset('storage/logo/logocheckout.png') }}" alt="Logo de La Casa del Chantilly" style="border: none; -ms-interpolation-mode: bicubic; max-width: 100%; width: 320px;">
                            </td>
                        </tr>

                        <!-- START MAIN CONTENT AREA -->
                        <tr>
                            <td style="box-sizing: border-box; padding: 20px 35px;">
                                <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td>
                                            <h1 style="color: #2c3e50; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-weight: 300; line-height: 1.4; margin: 0; margin-bottom: 30px; font-size: 35px; text-align: center;">
                                                {{ $complaint->type_complaint === 'Reclamo' ? '📋 Reclamo Registrado' : '📋 Queja Registrada' }}
                                            </h1>
                                            
                                            <p style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">
                                                Estimado/a <strong>{{ $complaint->customer_name }} {{ $complaint->customer_lastname }}</strong>,
                                            </p>
                                            
                                            <p style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 30px;">
                                                Hemos recibido su {{ strtolower($complaint->type_complaint) }} y ha sido registrado exitosamente en nuestro sistema.
                                            </p>
                                            
                                            <h2 style="color: #34495e; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-weight: 400; line-height: 1.4; margin: 0; margin-bottom: 15px; font-size: 20px;">
                                                Detalles del {{ $complaint->type_complaint }}
                                            </h2>
                                            
                                            <table style="width: 100%; margin-bottom: 20px;">
                                                <tr>
                                                    <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; padding: 5px 0;"><strong>Número:</strong></td>
                                                    <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; padding: 5px 0;">{{ $complaint->number_complaint }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; padding: 5px 0;"><strong>Fecha:</strong></td>
                                                    <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; padding: 5px 0;">{{ \Carbon\Carbon::parse($complaint->date_complaint)->format('d/m/Y') }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; padding: 5px 0;"><strong>Tipo:</strong></td>
                                                    <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; padding: 5px 0;">{{ $complaint->type_complaint }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; padding: 5px 0;"><strong>Bien contratado:</strong></td>
                                                    <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; padding: 5px 0;">{{ $complaint->well_hired }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; padding: 5px 0;"><strong>Monto:</strong></td>
                                                    <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; padding: 5px 0;">S/ {{ number_format($complaint->amount, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; padding: 5px 0;"><strong>Orden:</strong></td>
                                                    <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; padding: 5px 0;">{{ $complaint->order }}</td>
                                                </tr>
                                            </table>

                                            <h2 style="color: #34495e; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-weight: 400; line-height: 1.4; margin: 0; margin-bottom: 15px; font-size: 20px;">
                                                Descripción del problema
                                            </h2>
                                            
                                            <div style="background: #f8f9fa; border-left: 4px solid #e74c3c; padding: 15px; margin-bottom: 20px; border-radius: 3px;">
                                                <p style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; margin: 0; line-height: 1.6;">
                                                    {{ $complaint->description }}
                                                </p>
                                            </div>

                                            <h2 style="color: #34495e; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-weight: 400; line-height: 1.4; margin: 0; margin-bottom: 15px; font-size: 20px;">
                                                Detalle del {{ strtolower($complaint->type_complaint) }}
                                            </h2>
                                            
                                            <div style="background: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px; border-radius: 3px;">
                                                <p style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; margin: 0; line-height: 1.6;">
                                                    {{ $complaint->detail_complaint }}
                                                </p>
                                            </div>

                                            @if($complaint->observations)
                                            <h2 style="color: #34495e; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-weight: 400; line-height: 1.4; margin: 0; margin-bottom: 15px; font-size: 20px;">
                                                Observaciones
                                            </h2>
                                            
                                            <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin-bottom: 20px; border-radius: 3px;">
                                                <p style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; margin: 0; line-height: 1.6;">
                                                    {{ $complaint->observations }}
                                                </p>
                                            </div>
                                            @endif

                                            <div style="border-top: 1px solid #eee; padding-top: 20px; margin-top: 30px;">
                                                <h2 style="color: #34495e; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-weight: 400; line-height: 1.4; margin: 0; margin-bottom: 15px; font-size: 18px;">
                                                    Datos de contacto registrados
                                                </h2>
                                                
                                                <table style="width: 100%; margin-bottom: 20px;">
                                                    <tr>
                                                        <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; padding: 5px 0;"><strong>📧 Email:</strong></td>
                                                        <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; padding: 5px 0;">{{ $complaint->email }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; padding: 5px 0;"><strong>📱 Teléfono:</strong></td>
                                                        <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; padding: 5px 0;">{{ $complaint->phone }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; padding: 5px 0;"><strong>📍 Dirección:</strong></td>
                                                        <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; padding: 5px 0;">
                                                            {{ $complaint->address }}, {{ $complaint->district }}, {{ $complaint->province }}, {{ $complaint->department }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; padding: 5px 0;"><strong>🆔 DNI/RUC:</strong></td>
                                                        <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; padding: 5px 0;">{{ $complaint->dni_ruc }}</td>
                                                    </tr>
                                                </table>
                                            </div>

                                            <div style="background: #e8f5e8; border: 1px solid #4caf50; border-radius: 5px; padding: 20px; margin: 30px 0; text-align: center;">
                                                <p style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 10px; color: #2e7d32;">
                                                    <strong>Su {{ strtolower($complaint->type_complaint) }} será atendido según nuestros procedimientos internos.</strong>
                                                </p>
                                                <p style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; font-weight: normal; margin: 0; color: #2e7d32;">
                                                    Le estaremos contactando a la brevedad posible.
                                                </p>
                                            </div>
                                            
                                            <p style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px; margin-top: 30px;">
                                                Gracias por confiar en nosotros.
                                            </p>
                                            
                                            <p style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; font-weight: normal; margin: 0;">
                                                Atentamente,<br>
                                                <strong>{{ config('app.name') }}</strong>
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <!-- END MAIN CONTENT AREA -->
                    </table>
                    <!-- END CENTERED WHITE CONTAINER -->

                    <!-- START FOOTER -->
                    <div style="clear: both; margin-top: 10px; text-align: center; width: 100%;">
                        <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
                            <tr>
                                <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 12px; vertical-align: top; padding-bottom: 10px; padding-top: 10px; color: #999999; text-align: center;">
                                    <span>Este es un correo automático, por favor no responder.</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <!-- END FOOTER -->

                </div>
            </td>
            <td>&nbsp;</td>
        </tr>
    </table>
</body>
</html>