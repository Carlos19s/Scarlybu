<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Nota de Pedido - {{ $order->numero_pedido }}</title>
    <style>
        body { font-family: Helvetica, Arial, sans-serif; font-size: 11px; color: #000; line-height: 1.4; }
        .header { width: 100%; }
        .col-left { width: 48%; float: left; }
        .col-right { width: 48%; float: right; }
        .clear { clear: both; }
        
        .logo-area { font-size: 32px; font-weight: bold; text-align: center; padding: 20px 0 40px 0; }
        
        .box { border: 1px solid #000; border-radius: 8px; padding: 10px; margin-bottom: 10px; }
        
        .doc-title { font-size: 16px; font-weight: bold; margin-bottom: 10px; }
        .company-name { font-weight: bold; margin-bottom: 5px; }
        
        .client-table { width: 100%; }
        .client-table td { padding: 3px 0; vertical-align: top; }
        .client-label { width: 120px; }
        
        .items { width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 10px; }
        .items th, .items td { border: 1px solid #000; padding: 6px; text-align: center; font-size: 10px; }
        .items th { font-weight: bold; }
        .text-left { text-align: left !important; }
        .text-right { text-align: right !important; }
        
        .totals-box { width: 35%; float: right; }
        .totals { width: 100%; border-collapse: collapse; }
        .totals td { border: 1px solid #000; padding: 5px; font-size: 10px; }
        
        .info-box { width: 60%; float: left; border: 1px solid #000; padding: 10px; font-size: 10px; }
        
        .payment-box { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .payment-box th, .payment-box td { border: 1px solid #000; padding: 5px; text-align: center; font-size: 10px; }
    </style>
</head>
<body>

    <div class="header">
        <div class="col-left">
            <div class="logo-area">
                Scarlybu
            </div>
            <div class="box">
                <div class="company-name">Scarlybu C.A.</div>
                <div>Dirección matriz: Av.Eugenio Espejo & Reinaldo Chavez</div>
                <div>Teléfono: +593 99 132 9846</div>
                <div>Correo: Por definir</div>
            </div>
        </div>
        
        <div class="col-right">
            <div class="box">
                <div style="font-size: 14px; margin-bottom: 10px;">CEDULA/RUC: 21239831</div>
                <div class="doc-title">NOTA DE PEDIDO</div>
                <div>No. {{ $order->numero_pedido }}</div>
                <br>
                <div>FECHA DE EMISIÓN: {{ $order->created_at->format('d/m/Y') }}</div>
                <br>
                <div>AMBIENTE: PRODUCCIÓN</div>
                <div>EMISIÓN: NORMAL</div>
                <br>
                <!-- Fake barcode just for aesthetics -->
                <div style="text-align: center; font-family: monospace; letter-spacing: 2px; font-size: 18px; margin-top: 10px; background: #eee; padding: 5px;">
                    || | || ||| || ||| || ||| ||
                </div>
                <div style="text-align: center; font-size: 9px; margin-top: 5px;">
                    (Documento sin validez tributaria SRI)
                </div>
            </div>
        </div>
        <div class="clear"></div>
    </div>

    <div class="box">
        <table class="client-table">
            <tr>
                <td class="client-label">Razón social / Nombres:</td>
                <td>{{ $order->cliente_nombre }}</td>
            </tr>
            <tr>
                <td class="client-label">Identificación (RUC/CI):</td>
                <td>{{ $order->cliente_documento }}</td>
            </tr>
            <tr>
                <td class="client-label">Fecha emisión:</td>
                <td>{{ $order->created_at->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td class="client-label">Dirección:</td>
                <td>{{ $order->cliente_direccion }}</td>
            </tr>
            <tr>
                <td class="client-label">Teléfono:</td>
                <td>{{ $order->cliente_telefono }}</td>
            </tr>
        </table>
    </div>

    <table class="items">
        <thead>
            <tr>
                <th width="10%">Cod. Principal</th>
                <th width="10%">Cantidad</th>
                <th width="40%">Descripción</th>
                <th width="12%">Precio Unitario</th>
                <th width="12%">Descuento</th>
                <th width="16%">Precio Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>{{ str_pad($item->product_id, 4, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $item->cantidad }}</td>
                <td class="text-left">{{ $item->product->nombre ?? 'Producto' }}</td>
                <td class="text-right">{{ number_format((float) $item->precio_unitario, 2) }}</td>
                <td class="text-right">0.00</td>
                <td class="text-right">{{ number_format((float) $item->subtotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals-box">
        <table class="totals">
            <tr>
                <td class="text-left">SUBTOTAL IVA 15%</td>
                <td class="text-right">{{ number_format((float) $order->subtotal, 2) }}</td>
            </tr>
            <tr>
                <td class="text-left">SUBTOTAL IVA 0%</td>
                <td class="text-right">0.00</td>
            </tr>
            <tr>
                <td class="text-left">TOTAL DE DESCUENTO</td>
                <td class="text-right">0.00</td>
            </tr>
            <tr>
                <td class="text-left">IVA 15%</td>
                <td class="text-right">{{ number_format((float) $order->iva, 2) }}</td>
            </tr>
            <tr>
                <td class="text-left" style="font-weight: bold;">VALOR TOTAL</td>
                <td class="text-right" style="font-weight: bold;">{{ number_format((float) $order->total, 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="info-box">
        <table class="payment-box">
            <thead>
                <tr>
                    <th class="text-left">Forma de pago</th>
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-left">Otros con utilización del sistema financiero (Transferencia)</td>
                    <td>{{ number_format($order->total, 2) }}</td>
                </tr>
            </tbody>
        </table>
        
        <strong>Información Adicional</strong><br>
        <div style="margin-top: 5px;">
            Correo del cliente: {{ $order->cliente_correo ?: 'N/A' }}<br>
            Notas del pedido: {{ $order->notas ?: 'N/A' }}<br><br>
            * Este documento es una nota de pedido interna y no representa una factura electrónica válida para crédito tributario.
        </div>
    </div>
    
    <div class="clear"></div>

</body>
</html>
