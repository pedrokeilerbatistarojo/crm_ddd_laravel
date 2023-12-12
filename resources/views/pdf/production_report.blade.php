<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Informe de Producción</title>

    <style>
        table, th, td {
            border: none;
            text-align: center;
            font-size: 12px;
        }
    </style>
</head>

<body>
<div style="font-size: 10px; border-bottom: 1px #333333 solid">
    <div>Balneario Thermas de Griñon</div>
</div>
<div style="width: 100%; text-align: center; font-weight: bold; font-size: 14px; border-bottom: 1px #333333 solid">
    INFORME DE PRODUCCIÓN
</div>
<div
    style="display: inline-block; border-bottom: 1px #333333 solid; font-size: 12px; margin-top: 5px; margin-bottom: 5px">
    <div style="display: inline-block; margin-right: 20px"><b>FECHA: </b>{!! date('d/m/Y') !!}</div>
</div>
<table style="width: 100%">
    <tr class="font-12">
        <th style="width: 60px;">Fecha</th>
        <th style="width: 150px; text-align: left;">Empresa</th>
        <th style="width: 150px; text-align: left;">Cliente</th>
        <th style="text-align: left;">Concepto</th>
        <th>Und</th>
        <th>Precio</th>
        <th>Iva</th>
        <th>Importe</th>
        <th>Dto.</th>
    </tr>
    @php($totalPrice = 0)
    @foreach ($data['records'] as $detail)
        @php($totalPrice += $detail->price * $detail->quantity)
        <tr>
            <td>{{ Carbon\Carbon::parse($detail->created_at)->format('d/m/Y') }}</td>
            <td style="text-align: left">{{ $detail->order->company->name }}</td>
            <td style="text-align: left">{{ $detail->order->client->name }}</td>
            <td style="text-align: left">{{ $detail->product_name }}</td>
            <td>{{ $detail->quantity }}</td>
            <td>{{ $detail->price }}</td>
            <td>21%</td>
            <td>{{ number_format($detail->price * $detail->quantity, 2, ',', '.') }}</td>
            <td>{{ $detail->order->discount }}</td>
        </tr>
    @endforeach
    <tr>
        <td colspan="3">&nbsp;</td>
        <td style="border-top: 1px #333333 solid">{{ number_format($totalPrice, 2, ',', '.') }}</td>
        <td colspan="2">&nbsp;</td>
    </tr>
</table>
</body>
