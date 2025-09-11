<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Restablecer Contraseña</title>
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
                                
                                <!-- Fallback text visible cuando imagen no carga -->
                                <div style="font-size: 24px; font-weight: bold; color: #2c3e50; margin-top: 10px; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif;">
                                    LA CASA DEL CHANTILLY
                                </div>
                            </td>
                        </tr>

                        <!-- START MAIN CONTENT AREA -->
                        <tr>
                            <td style="box-sizing: border-box; padding: 20px 35px;">
                                <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td>
                                            <h1 style="color: #2c3e50; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-weight: 300; line-height: 1.4; margin: 0; margin-bottom: 30px; font-size: 35px; text-align: center;">Restablecer Contraseña</h1>
                                            
                                            <p style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">Hola <strong>{{ $name }}</strong>,</p>
                                            
                                            <p style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 30px;">Haz clic en el botón de abajo para restablecer tu contraseña.</p>
                                            
                                            <!-- Botón de acción -->
                                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="box-sizing: border-box; width: 100%; margin: 30px 0;">
                                                <tr>
                                                    <td align="center">
                                                        <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                                            <tr>
                                                                <td>
                                                                    <a href="{{ $url }}" target="_blank" style="background-color: #e74c3c; border: solid 1px #e74c3c; border-radius: 5px; box-sizing: border-box; color: #ffffff; cursor: pointer; display: inline-block; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; font-weight: bold; margin: 0; padding: 12px 25px; text-decoration: none; text-transform: capitalize;">Restablecer contraseña</a>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                            
                                            <p style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px; margin-top: 30px;">Si no solicitaste este cambio, puedes ignorar este mensaje.</p>
                                            
                                            <p style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-top: 30px;">
                                                Saludos,<br>
                                                <strong>La Casa del Chantilly</strong>
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
                                    <span>© {{ date('Y') }} La Casa del Chantilly. Todos los derechos reservados.</span>
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