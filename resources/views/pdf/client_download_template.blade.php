<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        @page {
            margin: 10px;
        }

        header {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            padding: 5px 0
        }

        .column {
            float: left;
            width: 50%;
            margin-top: 32px;
        }

        /* Clear floats after the columns */
        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        table,
        th,
        td {
            text-transform: uppercase;
            font-weight: bold;
            height: 11px !important;
            font-size: 9px;
            border: 1px #333333 solid;
            border-collapse: collapse;
        }
        .content-text{
            padding: 10px 25px 10px 25px
        },
        h3{
            font-family: Arial, Helvetica, sans-serif;
        },
        p{
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
        },
        .font-weight-bold{
            font-weight: 700!important;
            margin-right: 5px;
        }
    </style>

</head>

<body>
<header>
<div style="display: flex; justify-content: center; width: 100%">
    <img src="{!! public_path('assets/images/logo.svg') !!}" alt="logo" srcset="">
</div>
</header>
<div class="content-text">
    <h3>CONSENTIMIENTO EXPRESO CLIENTES THERMAS DE GRIÑÓN</h3>

    <p>Dni / Nif: <span class="font-weight-bold">{{!empty($data['client']->document) ? $data['client']->document : '-'}}</span>
        Fecha de Nacimiento: <span class="font-weight-bold">{{!empty($data['client']->birthdate) ? date('d/m/Y', strtotime($data['client']->birthdate)) : "-"}}</span></p>

    <p>Nombre y Apellidos: <span class="font-weight-bold">{{!empty($data['client']->name) ? $data['client']->name : '-'}}</span></p>

    <p>Domicilio: <span class="font-weight-bold">{{!empty($data['client']->address) ? $data['client']->address : "-"}}</span></p>

    <p>
        Localidad: <span class="font-weight-bold">{{!empty($data['client']->locality) ? $data['client']->locality->locality : '-'}}</span>
        Provincia: <span class="font-weight-bold">{{!empty($data['client']->locality) && !empty($data['client']->locality->province) ? $data['client']->locality->province->name : '-'}}</span>
        Codigo Postal: <span class="font-weight-bold">{{!empty($data['client']->postcode) ? $data['client']->postcode : '-'}}</span></p>

    <p>Telefono de Contacto: <span class="font-weight-bold">{{!empty($data['client']->phone) ? $data['client']->phone : '-'}}</span>
         Email: <span class="font-weight-bold">{{!empty($data['client']->email) ? $data['client']->email : '-'}}</span></p>

    <p>De conformidad con lo establecido en el Reglamento (UE) 2016/679 del Parlamento Europeo y del Consejo de 27 de
        abril de 2016(RGPD), y la Ley Orgánica 3/2018, de 5 de diciembre, de Protección de Datos Personales y garantía de
        los derechos digitales, le informamos que los datos de carácter personal obtenidos por las partes durante la ejecución
        del presente contrato, serán los estrictamente necesarios para el cumplimiento del mismo, así como para la
        prospección comercial de productos relacionados con nuestra actividad. Los datos serán incorporados al sistema de
        tratamiento titularidad de THERMAS DE GRIÑÓN S.L con CIF B-83720698 y domicilio social sito en Carretera Griñón /
        Torrejón de la Calzada KM 3,200 28971 Madrid, para el cumplimiento de los fines objeto del contrato.</p>

    <p>Sus datos no serán cedidos o entregados a terceros bajo título alguno, ni siquiera a efectos de mera conservación salvo
        a entidades financieras, con la única finalidad de cumplir con la prestación del servicio objeto del contrato. El hecho de
        no facilitar los datos a las entidades mencionadas implica que no se pueda cumplir con la prestación de los servicios.
        En caso de contratar servicios en el Gimnasio sus datos serán cedidos al titular profesional de dicha actividad mercantil.</p>

    <p><strong>THERMAS DE GRIÑÓN S.L</strong> reconoce cumplir con todas las obligaciones derivadas del RGPD, en especial, las
        relativas al derecho de información, consentimiento y deber de secreto, y a la adopción de las medidas de seguridad
        técnicas y organizativas que garanticen la seguridad de los datos personales a fin de garantizar y poder demostrar que
        el tratamiento es conforme con el RGPD.</p>

    <p><strong>THERMAS DE GRIÑÓN S.L</strong> informa que procederá a tratar los datos de manera lícita, leal, transparente, adecuada,
        pertinente, limitada, exacta y actualizada. Es por ello que THERMAS DE GRIÑÓN S.L se compromete a adoptar todas
        las medidas razonables para que estos se supriman o rectifiquen sin dilación cuando sean inexactos.
        THERMAS DE GRIÑÓN S.L se obliga a mantener, de forma permanente, el carácter confidencial de la información a la que tenga
        acceso con ocasión de la ejecución del contrato.</p>

    <p>De acuerdo con los derechos que le confiere la normativa vigente en protección de Datos de Carácter Personal, podrá
        ejercer los derechos de acceso, rectificación, limitación de tratamiento, supresión, portabilidad y oposición, dirigiendo su
        petición a la dirección postal indicada más arriba o bien a través de correo electrónico info@thermasdegrinon.com o
        al teléfono +34 918103526</p>

    <p style="font-weight: 700">Autoriza a THERMAS DE GRIÑON para que utilice la información que proporcione en este formulario para
        mantenerle al día de sus novedades y remitirle información comercial.</p>

</div>
</body>
