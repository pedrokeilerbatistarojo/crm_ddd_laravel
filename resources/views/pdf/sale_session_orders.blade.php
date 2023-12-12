<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Cierre de Caja - Compras</title>

    <style>
        table, th, td {
            border: none;
            text-align: center;
            font-size: 12px;
        }
    </style>
</head>

<body>
@php($n=1)
@foreach($data['companies'] as $company => $orders)
    <div style="font-size: 10px; border-bottom: 1px #333333 solid">
        <div style="float: left">{{$company}}</div>
        <div style="float: right">{!! date('d/m/Y') !!}</div>
        <div style="clear: both"></div>
    </div>
    <div style="width: 100%; text-align: center; font-weight: bold; font-size: 14px; border-bottom: 1px #333333 solid">
        CIERRE DE CAJA
    </div>
    <div
        style="display: inline-block; border-bottom: 1px #333333 solid; font-size: 12px; margin-top: 5px; margin-bottom: 5px">
        <div style="display: inline-block; margin-right: 20px"><b>CAJA: </b>{{ $data['record']->id }}</div>
        <div style="display: inline-block; margin-right: 20px"><b>TURNO: </b>{{ $data['record']->session_type->value}}
        </div>
        <div style="display: inline-block;">
            <b>FECHA: </b>{{ $data['record']->end_date ? $data['record']->end_date->format('d/m/Y') : '' }}</div>
    </div>
    <table style="width: 100%">
        <tr class="font-12">
            <th>Número</th>
            <th>Documento</th>
            <th style="width: 80px">Fecha</th>
            <th>Importe</th>
            <th>Forma Pago</th>
        </tr>
        @php($totalPrice = 0)
        @php($paymentsType = ['Efectivo' => 0, 'Tarjeta de Crédito' => 0, 'Transferencia' => 0])
        @foreach ($orders as $index => $item)
            @php($totalPrice += $item->total_price)
            @foreach($item->payments as $payment)
                @php($paymentsType[$payment['type']] = $paymentsType[$payment['type']] + $payment['amount'])
            <tr>
                <td>{{ $item->ticket_number }}</td>
                <td>Ticket</td>
                <td style="width: 80px">{{ Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</td>
                <td style="text-align: right">{{ number_format($item->total_price, 2, ',', '.') }}</td>
                <td>{{ $payment['type'] }}</td>
            </tr>
           @endforeach
        @endforeach
        <tr><td colspan="6">&nbsp;</td></tr>
        <tr>
            <td colspan="3" style="text-align: right">Efectivo</td>
            <td style="border-top: 1px #333333 solid; text-align: right">{{ number_format($paymentsType['Efectivo'], 2, ',', '.') }}</td>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3" style="text-align: right">Tarjeta</td>
            <td style="text-align: right">{{ number_format($paymentsType['Tarjeta de Crédito'], 2, ',', '.') }}</td>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3" style="text-align: right">Transferencia</td>
            <td style="text-align: right">{{ number_format($paymentsType['Transferencia'], 2, ',', '.') }}</td>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="1">Nº Documentos: {{count($orders)}}</td>
            <td colspan="2" style="text-align: right">Total</td>
            <td style="border-top: 1px #333333 solid; text-align: right">{{ number_format($totalPrice, 2, ',', '.') }}</td>
            <td colspan="2">&nbsp;</td>
        </tr>
    </table>
    @php($n++)
    @if($n <= count($data['companies']))
        <div style="page-break-after: always;"></div>
    @endif
@endforeach
</body>
