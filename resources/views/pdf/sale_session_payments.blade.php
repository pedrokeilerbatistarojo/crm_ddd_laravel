<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Cierre de Caja - Pagos</title>

    <style>
        table, th, td {
            border: none;
            text-align: center;
            font-size: 12px;
        }

        div.page_break + div.page_break {
            page-break-before: always;
        }
    </style>
</head>

<body>
@php($n=1)
@foreach ($data['payments'] as $company => $payments)
    <div style="font-size: 10px; border-bottom: 1px #333333 solid">
        <div style="float: left">{{$company}}</div>
        <div style="float: right">{!! date('d/m/Y') !!}</div>
        <div style="clear: both"></div>
    </div>
    <div style="width: 100%; text-align: center; font-weight: bold; font-size: 14px; border-bottom: 1px #333333 solid">
        INFORME DE COBROS
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
            <th style="border: 1px #333333 solid">Documento</th>
            <th style="border: 1px #333333 solid">Número</th>
            <th style="border: 1px #333333 solid">Tipo</th>
            <th style="border: 1px #333333 solid">Cliente</th>
            <th style="border: 1px #333333 solid">Fecha</th>
            <th style="border: 1px #333333 solid">Importe</th>
        </tr>
        @php($allTotal = 0)
        @php($paymentsType = ['Efectivo' => 0, 'Tarjeta de Crédito' => 0, 'Transferencia' => 0])
        @foreach ($payments as $type => $items)
            @php($totalPrice = 0)
            <tr>
                <td colspan="5"
                    style="border-bottom: 1px #333333 solid; text-align: left; font-size: 14px; font-weight: bold">{{ $type }}</td>
            </tr>
            @foreach ($items as $index => $item)
                @php($totalPrice += $item['amount'])
                @php($allTotal += $item['amount'])
                @php($paymentsType[$type] = $paymentsType[$type] + $item['amount'])
                <tr>
                    <td>Ticket</td>
                    <td>{{ $item['ticket_number'] }}</td>
                    <td>{{ $item['type'] }}</td>
                    <td>{{ $item['client']  }}</td>
                    <td style="width: 80px">{{ $item['paid_date'] }}</td>
                    <td>{{ number_format($item['amount'], 2, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="3">&nbsp;</td>
                <td style="border-top: 1px #333333 solid">{{ number_format($totalPrice, 2, ',', '.') }}</td>
                <td colspan="1">&nbsp;</td>
            </tr>
        @endforeach
        <tr><td colspan="5">&nbsp;</td></tr>
        <tr><td colspan="5">&nbsp;</td></tr>
        <tr>
            <td colspan="2" style="text-align: right">&nbsp;</td>
            <td style="text-align: right;">Efectivo</td>
            <td style="border-top: 1px #333333 solid; text-align: right">{{ number_format($paymentsType['Efectivo'], 2, ',', '.') }}</td>
            <td colspan="1">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: right">&nbsp;</td>
            <td style="text-align: right">Tarjeta</td>
            <td style="text-align: right">{{ number_format($paymentsType['Tarjeta de Crédito'], 2, ',', '.') }}</td>
            <td colspan="1">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: right">&nbsp;</td>
            <td style="text-align: right">Transferencia</td>
            <td style="text-align: right">{{ number_format($paymentsType['Transferencia'], 2, ',', '.') }}</td>
            <td colspan="1">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: right">&nbsp;</td>
            <td style="text-align: right;">Total</td>
            <td style="border-top: 1px #333333 solid; text-align: right">{{ number_format($allTotal, 2, ',', '.') }}</td>
            <td colspan="1">&nbsp;</td>
        </tr>
    </table>
    @php($n++)
    @if($n <= count($data['payments']))
        <div style="page-break-after: always;"></div>
    @endif
@endforeach
</body>
