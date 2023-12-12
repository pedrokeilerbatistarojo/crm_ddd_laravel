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
            position: fixed;
            top: 0px;
            left: 0px;
            right: 0px;
            height: 20px;
            text-align: center;
            font-weight: bold;
            font-size: 9px;
            border: 1px rgb(211, 209, 209) solid;
            padding: 5px 0
        }
        .table{
            margin-top: 50px;
            width: 100%;
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
            height: 12px !important;
            font-size: 9px;
            border: 1px rgb(211, 209, 209) solid;
            border-collapse: collapse;
            vertical-align: middle!important;
        }
        .markReservation{
            background-color: rgb(211, 209, 209);
            padding: 5px;
            vertical-align: top!important;
        }
        .centerContent{
            display: flex;
            align-items: center;
            justify-content: center
        }
        .no-border{
            border: 1px solid rgb(211, 209, 209);
        }
        .markNote{
            display: block;
            margin-top: 5px;
            margin-bottom: 5px;
        }

    </style>

</head>

<body>
<header>
    {{$data['date']}}
</header>

@php($count = 2)
@php($start = 0)
@php($end = 7)
@php($continueTd = [])
@php($countSchedules = count($data['schedules']))
@php($duration = 0)
<table class="table">
    <thead>
        <tr>
            <th>{{$data['day']}}</th>
            <th>
                {{$data['employee']->first_name . ' ' . $data['employee']->last_name}}
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data['schedules'] as $blockIndex => $item)
            <tr>
                <td style="width: 10%">{{$item['name']}}</td>
                @php($dataTd = null)
                @php($markTimeOff = false)
                @foreach($data['employeesTimeOff'] as $timeOff)
                        <?php
                        if($timeOff->type === 'Día Completo'){
                            $markTimeOff = true;
                        }else{
                            $dayCurrent = date('Y-m-d',strtotime($timeOff->from_date));
                            $from_date = strtotime($timeOff->from_date);
                            $to_date   = strtotime($timeOff->to_date);
                            $itemSchedule = strtotime("{$dayCurrent} {$item['start']}:00");
                            if($from_date <= $itemSchedule && $to_date >= $itemSchedule){
                                $markTimeOff = true;
                            }
                        }
                        ?>
                @endforeach
                @php($markNote = null)
                @foreach($data['treatmentScheduleNotes'] as $note)
                        <?php
                        $dayCurrent = date('Y-m-d',strtotime($note->date));
                        $from_date = strtotime("{$dayCurrent} {$note->from_hour}:00");
                        $to_date   = strtotime("{$dayCurrent} {$note->to_hour}:00");
                        $itemSchedule = strtotime("{$dayCurrent} {$item['start']}:00");
                        if($from_date <= $itemSchedule && $to_date >= $itemSchedule){
                            $markNote = $note->note;
                        }
                        ?>
                @endforeach
                @if ($duration > 0)
                    <td style="width: 90%"class="markReservation">
                        @if($markTimeOff)
                            No disponible
                        @endif
                        @if($markNote)
                            <div class="markNote">
                                {{$markNote}}
                            </div>
                        @endif
                    </td>
                    @php($duration--)
                @else
                    @foreach ($data['records'] as $key => $record)
                        @if (isset($continueTd[$data['employee']->id]) && $continueTd[$data['employee']->id]['id'] == $data['employee']->id)
                                <?php
//                                $dataTd['product_name'] = $continueTd[$data['employee']->id]['td']['product_name'];
                                $dataTd['client']       = $continueTd[$data['employee']->id]['td']['client'];
                                $dataTd['phone']        = $continueTd[$data['employee']->id]['td']['phone'];
                                $dataTd['notes']        = $continueTd[$data['employee']->id]['td']['notes'];
                                $duration = $continueTd[$data['employee']->id]['duration'];
                                unset($continueTd[$data['employee']->id]);
                                ?>
                        @elseif ($record->time == $item['start'] && $record->employee_id == $data['employee']->id)
                                <?php
//                                $dataTd['product_name'] = $record->orderDetails[0]['product_name'];
                                $dataTd['client']       = $record->client->name;
                                $dataTd['phone']        = $record->client->phone;
                                $dataTd['notes']        = $record->notes;
                                $duration = $record->duration / 30;
                                ?>
                        @endif
                    @endforeach
                    @if ($dataTd)
                        <td class="markReservation">
{{--                            <div>{{$dataTd['product_name']}}</div>--}}
                            <div>{{$dataTd['client']}}</div>
                            <div>{{$dataTd['phone']}}</div>
                            <div>{{$dataTd['notes']}}</div>
                            @if($markNote)
                                <div class="markNote">
                                    {{$markNote}}
                                </div>
                            @endif
                        </td>
                        @php($duration--)
                        @if ($duration > 0 & $blockIndex)
                                <?php
                                $continueTd[$employee->id]['id'] = $employee->id;
                                $continueTd[$employee->id]['td'] = $dataTd;
                                $continueTd[$employee->id]['duration'] = $duration;
                                ?>
                        @endif
                    @else
                        <td @if($markTimeOff || $markNote) class="markReservation" @endif>
                            @if($markTimeOff)
                                No disponible
                            @endif
                            @if($markNote)
                                <div class="markNote">
                                    {{$markNote}}
                                </div>
                            @endif
                        </td>
                    @endif
            @endif
            </tr>
        @endforeach

    </tbody>
</table>

</body>
