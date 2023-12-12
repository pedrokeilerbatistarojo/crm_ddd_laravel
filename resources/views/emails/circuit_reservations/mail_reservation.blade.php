@extends('emails.layouts.email')

@section('content')
    <style>
        p{
            color: black;
        }
        li{
            color: black;
        }
    </style>

    <p>Estimad@ {{$record->client->name}}, le recordamos su cita con nosotros el día {{ Carbon\Carbon::parse($record->date)->format('d/m/Y') }} a las {{$record->time}} horas y para {{$record->adults + $record->children}} personas.</p>

    <ul>
        <li style="margin-bottom: 5px">Si ya tiene su reserva pagada recuerde <strong>traer el localizador de la compra </strong> (impreso o digital), <strong>cheque físico</strong> adquirido en el centro (imprescindible la presentación del mismo) o el <strong>justificante de su compra</strong>.</li>
        <li style="margin-bottom: 5px">Es necesario traer <strong>bañador o bikini, gorro y toalla</strong>.</li>
        <li style="margin-bottom: 5px">El <strong>uso de gorro es obligatorio</strong> durante todo el circuito (no son válidos los gorros de ducha). El calzado lo entregamos con la entrada y son de uso obligatorio.</li>
        <li style="margin-bottom: 5px">En Thermas de Griñón disponemos de gorros a la venta por 3 euros. Las toallas las puede alquilar por 2 euros. Le recordamos que el material de alquiler lo tiene que depositar junto con el “ticket de salida” en el mostrador de recepción antes de abandonar el centro.</li>
        <li style="margin-bottom: 5px">Los <strong>vestuarios son mixtos y disponen de cabinas individuales</strong> para cambiarse de ropa. Las taquillas funcionan con monedas de 1 euro.</li>
    </ul>

    <p>Muchas gracias</p>

@endsection

