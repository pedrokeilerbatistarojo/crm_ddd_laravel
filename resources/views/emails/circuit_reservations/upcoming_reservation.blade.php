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

    Hola, {{$record->client->name}}.

    Le escribimos para recordarle que mañana {{ Carbon\Carbon::parse($record->date)->format('d/m/Y') }} a las {{$record->time}} tiene su reserva.

    No olvide ser puntual.

    Gracias,

    {{ config('app.name') }}

@endsection



{{--@component('mail::message')--}}

{{--@endcomponent--}}
