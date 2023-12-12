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

    <p>Estimad@ {{$record->client->name}}, su cita con nosotros es el día {{ Carbon\Carbon::parse($record->date)->format('d/m/Y') }} a las {{$record->time}} horas.</p>

    <ul>
        <li style="margin-bottom: 5px">Recuerde <strong>traer el localizador de la compra </strong> (impreso o digital), <strong>cheque físico</strong> adquirido en el centro (imprescindible la presentación del mismo) o el <strong>justificante de su compra</strong>.</li>
    </ul>

    <p>Muchas gracias</p>

@endsection

