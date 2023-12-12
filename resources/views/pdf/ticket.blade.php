<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Ticket Compra</title>

    <style>
        body,
        html {
            color: black;
            font-size: 13px;
            margin: 10px 10px;
            padding: 0;
            font-family: 'courier'
        }

        table,
        th,
        td {
            border: none;
            text-align: center;
            font-size: 13px;
        }
    </style>
</head>

<body>
    <div style="width: 100%; text-align: center">
        <div>{!! $data['order']->company->name !!}</div>
        <div>CIF: {!! $data['order']->company->cif !!}</div>
        <div style="padding:0 20px">{!! $data['order']->company->address !!}</div>
        <div>{!! $data['order']->company->locality !!} {!! $data['order']->company->zip_code !!} {!! $data['order']->company->province !!}</div>
        <div>Tel.: {!! $data['order']->company->phone !!}</div>
        <div>Email: {!! $data['order']->company->email !!}</div>
    </div>
    <br>
    <table style="width: 100%">
        <tr>
            <td colspan="4">
                <div style="text-align:center">Factura Simplificada</div>
            </td>
        </tr>
        <tr>
            <td style="text-align: left">Número:</td>
            <td colspan="3" style="text-align: left">{{ $data['order']->ticket_number }}</td>
        </tr>
        <tr>
            <td style="text-align: left">Fecha:</td>
            <td style="text-align: left">{{ Carbon\Carbon::parse($data['order']->created_at)->format('d/m/Y') }}</td>
            <td style="text-align: left">Hora:</td>
            <td style="text-align: left">{{ Carbon\Carbon::parse($data['order']->created_at)->format('H:i') }}</td>
        </tr>
        <tr>
            <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
            <td>Und.</td>
            <td style="text-align: left">Descripción</td>
            <td>Precio</td>
            <td style="text-align: right">Importe</td>
        </tr>
        <tr style="height: 1px">
            <td colspan="4" style="border-top: 1px #333333 solid; height: 1px; font-size: 1px">&nbsp;</td>
        </tr>
        @php($totalPrice = 0)
        @foreach ($data['order']->orderDetails as $detail)
            @php($totalPrice += $detail['price'] * $detail['quantity'])
            <tr>
                <td>{{ $detail['quantity'] }}</td>
                <td style="text-align: left">{{ $detail['product_name'] }}</td>
                <td>{{ number_format($detail['price'], 2, ',', '.') }}</td>
                <td style="text-align: right">{{ number_format($detail['price'] * $detail['quantity'], 2, ',', '.') }}
                </td>
            </tr>
        @endforeach
        @php($ivaPercentage = config('system.iva'))
        @php($iva = $totalPrice - $totalPrice / floatval('1.' . config('system.iva')))
        <tr style="height: 1px">
            <td colspan="4" style="border-top: 1px #333333 solid; height: 1px; font-size: 1px">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3" style="text-align: right">Total</td>
            <td style="text-align: right">{{ number_format($totalPrice - $iva, 2, ',', '.') }}</td>
        </tr>
        <tr>
            <td colspan="3" style="text-align: right">IVA ({{ $ivaPercentage }}%)</td>
            <td style="text-align: right">{{ number_format($iva, 2, ',', '.') }}</td>
        </tr>
        <tr>
            <td colspan="3" style="text-align: right">Total</td>
            <td style="text-align: right">{{ number_format($totalPrice, 2, ',', '.') }}</td>
        </tr>
        <tr>
            <td colspan="4">&nbsp;</td>
        </tr>
    </table>
    <table>
        <tr>
            <td style="text-align: left">Forma de Pago:</td>
            <td style="text-align: left">{{ collect($data['order']->payments)->pluck('type')->join(',') }}</td>
        </tr>
    </table>
    @if (!empty($data['order']->client))
        <table>
            <tr>
                <td style="text-align: left">Cliente:</td>
                <td style="text-align: left">
                    @if ($data['order']->type === \Domain\Orders\Enums\OrderType::CLIENT->value)
                        {{ $data['order']->client->name }}
                    @endif
                    @if ($data['order']->type === \Domain\Orders\Enums\OrderType::TELEPHONE_SALE->value)
                        VT {{ $data['order']->telephone_sale_seq }}
                    @endif
                    @if ($data['order']->type === \Domain\Orders\Enums\OrderType::COUNTER_SALE->value)
                        CH {{ $data['order']->counter_sale_seq }}
                    @endif
                </td>
            </tr>
        </table>
    @endif
</body>
