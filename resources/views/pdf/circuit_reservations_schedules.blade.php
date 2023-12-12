<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        @page {
            margin: 10px;
        }

        body {
            font-weight: bold !important;
            font-family: sans-serif;
        }

        header {
            position: fixed;
            top: 0px;
            left: 0px;
            right: 0px;
            height: 17px;
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            border: 0.5px #333333 solid;
            padding: 1px 0
        }

        .column {
            float: left;
            width: 50%;
            margin-top: 20px;
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
            height: 10px !important;
            font-size: 10px;
            border: 0.5px #333333 solid;
            border-collapse: collapse;
        }
    </style>

</head>

<body>
    <header>
        {{ $data['date'] }}
    </header>
    <div class="row">
        <?php
            $restRows = 1;
            $keyRows = 'rows';
            if ($data['dayOfWeek'] == 6) {
                $keyRows = 'rows_saturday';
            }
            if ($data['dayOfWeek'] == 0) {
                $keyRows = 'rows_sunday';
            }
        ?>

        @php($tablesPrinted = 0)
        @foreach ($data['schedules'] as $blockIndex => $chunks)
            <div class="column">
                @foreach ($chunks as $schedules => $item)
                    @php($tablesPrinted++)
                    <table style="width: 100%">
                        <thead>
                            <tr>
                                <td colspan="4" style="text-align: center;">
                                    {{ $item['name'] }}
                                </td>
                            </tr>
                            <tr>
                                <th style="width: 20px"></th>
                                <th style="text-align: left">&nbsp;&nbsp;NOMBRE COMPLETO</th>
                                <th style="width: 70px">TELÉFONO</th>
                                <th style="width: 20px">NP</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php($totalRows = $item[$keyRows] - $restRows)
                            @php($totalNP = 0)
                            @php($items = $data['records']->where('time', $item['start']))
                            @php($rows = $items->count())
                            @php($itemsDelayed = collect())
                            @php($currentRow = 1)
                            @php($product = null)
                            @php($totalMassage = 0)

                            @foreach ($items as $index => $reservation)
                                @php($isAllReservesOnSameDay = false)
                                @php($lineNote = null)

                                @if (!empty($reservation->schedule_note))
                                    @php($lineNote = $reservation->schedule_note)
                                @elseif($reservation->orderDetails && isset($reservation->orderDetails[0]['product']['productType']))
                                    @php($lineNote = $reservation->orderDetails[0]['product']['productType']['name'] ?? null)
                                @endif

                                @if ($reservation->orderDetails && isset($reservation->orderDetails[0]['product']))
                                    <?php
                                    $product = $reservation->orderDetails[0]['product'];
                                    $isAllReservesOnSameDay = (bool) $product['all_reserves_on_same_day'];
                                    ?>
                                @endif

                                @if (!empty($reservation->treatment_reservations))
                                    @php($totalMassage += $reservation->treatment_reservations)
                                @elseif ($isAllReservesOnSameDay)
                                    @php($totalMassage += $reservation->adults + $reservation->children)
                                @else
                                    <tr>
                                        <td style="text-align: center;"> {{ $currentRow++ }} </td>
                                        <td style="text-align: left;">
                                            <div style="overflow: hidden; height: 10px">
                                                @if ($reservation->orderDetails)
                                                    @if ($reservation->orderDetails[0]['order']['type'] === \Domain\Orders\Enums\OrderType::CLIENT->value)
                                                        {{ $reservation->client->name }}
                                                    @endif
                                                    @if ($reservation->orderDetails[0]['order']['type'] === \Domain\Orders\Enums\OrderType::TELEPHONE_SALE->value)
                                                        VT
                                                        {{ $reservation->orderDetails[0]['order']['telephone_sale_seq'] }}
                                                    @endif
                                                    @if ($reservation->orderDetails[0]['order']['type'] === \Domain\Orders\Enums\OrderType::COUNTER_SALE->value)
                                                        CH
                                                        {{ $reservation->orderDetails[0]['order']['counter_sale_seq'] }}
                                                    @endif
                                                @else
                                                    {{ $reservation->client->name }}
                                                @endif
                                                @if (!empty($lineNote))
                                                    ({{ $lineNote }})
                                                @endif
                                            </div>
                                        </td>
                                        <td style="text-align: center;">
                                            {{ $reservation->client->phone }}
                                        </td>
                                        <td style="text-align: center;">
                                            {{ $reservation->adults + $reservation->children }}
                                        </td>
                                    </tr>
                                    <?php
                                    $totalNP += $reservation->adults + $reservation->children; ?>
                                @endif
                            @endforeach

                            @if ($item['start'] !== $item['start_delayed'])
                                @php($itemsDelayed = $data['records']->where('time', $item['start_delayed']))
                                @php($pendingRows = $totalRows - $rows)
                                @if ($itemsDelayed->count() < $pendingRows)
                                    @php($repeats = ($pendingRows - $itemsDelayed->count()) / 2)
                                    @foreach (range(1, $repeats) as $empty)
                                        <tr>
                                            <td style="text-align: center;"> {{ $currentRow++ }}</td>
                                            <td style="text-align: center;"> &ensp;</td>
                                            <td style="text-align: center;"> &ensp;</td>
                                            <td style="text-align: center;"> &ensp;</td>
                                        </tr>
                                    @endforeach
                                @endif
                                <tr>
                                    <td style="text-align: center;"> {{ $currentRow++ }}</td>
                                    <td colspan="3" style="text-align: center; font-weight: bold">
                                        {{ $item['start_delayed'] }}</td>
                                </tr>
                                @foreach ($itemsDelayed as $index => $reservation)
                                    @php($isAllReservesOnSameDay = false)
                                    @php($lineNote = null)

                                    @if (!empty($reservation->schedule_note))
                                        @php($lineNote = $reservation->schedule_note)
                                    @elseif($reservation->orderDetails && isset($reservation->orderDetails[0]['product']['productType']))
                                        @php($lineNote = $reservation->orderDetails[0]['product']['productType']['name'] ?? null)
                                    @endif

                                    @if ($reservation->orderDetails && isset($reservation->orderDetails[0]['product']))
                                        <?php
                                        $product = $reservation->orderDetails[0]['product'];
                                        $isAllReservesOnSameDay = (bool) $product['all_reserves_on_same_day'];
                                        ?>
                                    @endif

                                    @if (!empty($reservation->treatment_reservations))
                                        @php($totalMassage += $reservation->treatment_reservations)
                                    @elseif ($isAllReservesOnSameDay)
                                        @php($totalMassage += $reservation->adults + $reservation->children)
                                    @else
                                        <tr>
                                            <td style="text-align: center;"> {{ $currentRow++ }}</td>
                                            <td style="text-align: left;">
                                                <div style="overflow: hidden; height: 10px;">
                                                    @if ($reservation->orderDetails)
                                                        @if ($reservation->orderDetails[0]['order']['type'] === \Domain\Orders\Enums\OrderType::CLIENT->value)
                                                            {{ $reservation->client->name }}
                                                        @endif
                                                        @if ($reservation->orderDetails[0]['order']['type'] === \Domain\Orders\Enums\OrderType::TELEPHONE_SALE->value)
                                                            VT
                                                            {{ $reservation->orderDetails[0]['order']['telephone_sale_seq'] }}
                                                        @endif
                                                        @if ($reservation->orderDetails[0]['order']['type'] === \Domain\Orders\Enums\OrderType::COUNTER_SALE->value)
                                                            CH
                                                            {{ $reservation->orderDetails[0]['order']['counter_sale_seq'] }}
                                                        @endif
                                                    @else
                                                        {{ $reservation->client->name }}
                                                    @endif
                                                    @if (!empty($lineNote))
                                                        ({{ $lineNote }})
                                                    @endif
                                                </div>
                                            </td>
                                            <td style="text-align: center;"> {{ $reservation->client->phone }} </td>
                                            <td style="text-align: center;">
                                                {{ $reservation->adults + $reservation->children }}</td>
                                        </tr>
                                        @php($totalNP += $reservation->adults + $reservation->children)
                                    @endif
                                @endforeach
                            @endif

                            @foreach (range(0, $totalRows - $currentRow) as $empty)
                                @if ($totalRows === $currentRow)
                                    <tr>
                                        <td style="text-align: center;"> {{ $currentRow++ }}</td>
                                        <td style="text-align: center;"> MASAJES</td>
                                        <td style="text-align: center;"> </td>
                                        <td style="text-align: center;"> {{ $totalMassage }}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td style="text-align: center;"> {{ $currentRow++ }}</td>
                                        <td style="text-align: center;"> &ensp;</td>
                                        <td style="text-align: center;"> &ensp;</td>
                                        <td style="text-align: center;"> &ensp;</td>
                                    </tr>
                                @endif
                            @endforeach
                            <tr>
                                <td style="text-align: center;">
                                    {{ $currentRow++ }}
                                </td>
                                <td style="text-align: center; font-weight: bold;">
                                    TOTAL PAX POR PASE
                                </td>
                                <td style="text-align: center;">

                                </td>
                                <td style="text-align: center;">
                                    {{ $totalNP + $totalMassage }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                @endforeach
            </div>
            @if ($tablesPrinted % 6 === 0 && !$loop->last)
                <div style="page-break-before: always;"></div>
            @endif
        @endforeach
    </div>
</body>
