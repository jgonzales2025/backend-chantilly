<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Confirmación de Pago</title>
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
                                            <h1 style="color: #2c3e50; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-weight: 300; line-height: 1.4; margin: 0; margin-bottom: 30px; font-size: 35px; text-align: center;">¡Pago confirmado!</h1>
                                            
                                            <p style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">Hola <strong>{{ $customer->name }}</strong>,</p>
                                            
                                            <p style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 30px;">Tu pago ha sido procesado exitosamente. Aquí tienes los detalles de tu pedido:</p>
                                            
                                            <h2 style="color: #34495e; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-weight: 400; line-height: 1.4; margin: 0; margin-bottom: 15px; font-size: 20px;">Detalles del Pedido</h2>
                                            
                                            <table style="width: 100%; margin-bottom: 20px;">
                                                <tr>
                                                    <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; padding: 5px 0;"><strong>Número de pedido:</strong></td>
                                                    <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; padding: 5px 0;">{{ $order->order_number }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; padding: 5px 0;"><strong>Fecha:</strong></td>
                                                    <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; padding: 5px 0;">{{ $order->order_date->format('d/m/Y H:i') }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; padding: 5px 0;"><strong>Total:</strong></td>
                                                    <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; padding: 5px 0;">S/ {{ number_format($order->total, 2) }}</td>
                                                </tr>
                                            </table>

                                            @if($order->local)
                                            <h2 style="color: #34495e; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-weight: 400; line-height: 1.4; margin: 0; margin-bottom: 15px; font-size: 20px;">Local de entrega</h2>
                                            <p style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 20px;">
                                                <strong>{{ $order->local->name }}</strong><br>
                                                {{ $order->local->address }}
                                            </p>
                                            @endif

                                            <h2 style="color: #34495e; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-weight: 400; line-height: 1.4; margin: 0; margin-bottom: 15px; font-size: 20px;">Productos</h2>
                                            
                                            @foreach($order->items as $item)
                                            <div style="background: #f8f9fa; border-left: 4px solid #e74c3c; padding: 15px; margin-bottom: 15px; border-radius: 3px;">
                                                <p style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; font-weight: bold; margin: 0; margin-bottom: 5px;">{{ $item->product?->short_description ?? $item->productVariant?->description ?? 'Producto no disponible' }}</p>
                                                
                                                @if($item->dedication_text)
                                                <p style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 13px; font-style: italic; margin: 0; margin-bottom: 5px; color: #7f8c8d;">Dedicatoria: "{{ $item->dedication_text }}"</p>
                                                @endif
                                                
                                                <table style="width: 100%; font-size: 13px;">
                                                    <tr>
                                                        <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; padding: 2px 0;"><strong>Cantidad:</strong> {{ $item->quantity }}</td>
                                                        <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; padding: 2px 0;"><strong>Subtotal:</strong> S/ {{ number_format($item->subtotal, 2) }}</td>
                                                    </tr>
                                                    @if($order->delivery_date)
                                                    <tr>
                                                        <td colspan="2" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; padding: 2px 0;"><strong>Fecha de entrega:</strong> {{ \Carbon\Carbon::parse($item->delivery_date)->format('d/m/Y') }}</td>
                                                    </tr>
                                                    @endif
                                                </table>
                                            </div>
                                            @endforeach

                                            @if($transaction)
                                            <h2 style="color: #34495e; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-weight: 400; line-height: 1.4; margin: 0; margin-bottom: 15px; font-size: 20px;">Detalles del Pago</h2>
                                            <table style="width: 100%; margin-bottom: 20px;">
                                                <tr>
                                                    <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; padding: 5px 0;"><strong>ID de transacción:</strong></td>
                                                    <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; padding: 5px 0;">{{ $transaction->transaction_id }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; padding: 5px 0;"><strong>Método:</strong></td>
                                                    <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; padding: 5px 0;">Tarjeta de crédito/débito (Niubiz)</td>
                                                </tr>
                                                <tr>
                                                    <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; padding: 5px 0;"><strong>Estado:</strong></td>
                                                    <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; padding: 5px 0; color: #27ae60;">Pagado ✅</td>
                                                </tr>
                                            </table>
                                            @endif

                                            <div style='background: {{ $order->pointHistories->point_type === "Acumulado" ? "#e8f5e8" : "#fff3cd" }}; border-left: 4px solid {{ $order->pointHistories->point_type === "Acumulado" ? "#28a745" : "#ffc107" }}; padding: 15px; margin-bottom: 20px; border-radius: 3px;'>
                                                @if($order->pointHistories->point_type === 'Acumulado')
                                                    <p style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; font-weight: bold; margin: 0; margin-bottom: 5px; color: #155724;">🎉 ¡Puntos Ganados!</p>
                                                    <p style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; margin: 0; color: #155724;">
                                                        Has ganado <strong>{{ number_format($order->pointHistories->points_earned) }} {{$order->pointHistories->points_earned == 1 ? 'punto' : 'puntos'}}</strong> con esta compra.
                                                    </p>
                                                @elseif($order->pointHistories->point_type === 'Canje')
                                                    <p style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; font-weight: bold; margin: 0; margin-bottom: 5px; color: #856404;">✨ Puntos Canjeados</p>
                                                    <p style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; margin: 0; color: #856404;">
                                                        Has canjeado <strong>{{ number_format(abs($order->pointHistories->points_earned)) }} puntos</strong> en esta compra.
                                                    </p>
                                                @else
                                                    <p style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; font-weight: bold; margin: 0; margin-bottom: 5px; color: #6c757d;">ℹ️ No Acumula Puntos</p>
                                                    <p style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; margin: 0; color: #6c757d;">
                                                        Esta compra no acumula puntos según las condiciones del pedido.
                                                    </p>
                                                @endif
                                                <p style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 13px; margin: 5px 0 0 0; color: #6c757d;">
                                                    Saldo actual: <strong>{{ number_format($customer->points ?? 0) }} puntos</strong>
                                                </p>
                                            </div>

                                            <p style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px; margin-top: 30px;">Nos estaremos comunicando contigo para coordinar la entrega de tu pedido.</p>
                                            
                                            <p style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; font-weight: bold; margin: 0; margin-bottom: 15px;">¡Gracias por tu compra!</p>
                                            
                                            <p style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; font-weight: normal; margin: 0;">
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