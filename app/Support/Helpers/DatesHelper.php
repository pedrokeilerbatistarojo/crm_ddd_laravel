<?php

namespace Support\Helpers;

use Illuminate\Support\Carbon;

class DatesHelper
{
    /**
     * @param string $date
     * @return array|string[][]
     */
    public static function dateSchedules(string $date): array
    {
        $date = Carbon::parse($date);

        $morningRanges = [
            [
                'name' => '10:00 - 13:00',
                'start' => '10:00',
                'start_delayed' => '10:00',
                'end' => '13:00',
                'rows' => 22,
                'rows_sunday' => 22,
                'rows_saturday' => 24
            ],
            [
                'name' => '10:30 - 13:30',
                'start' => '10:30',
                'start_delayed' => '10:30',
                'end' => '13:30',
                'rows' => 22,
                'rows_sunday' => 23,
                'rows_saturday' => 22
            ],
            [
                'name' => '11:00 - 14:00',
                'start' => '11:00',
                'start_delayed' => '11:30',
                'end' => '14:00',
                'rows' => 23,
                'rows_sunday' => 23,
                'rows_saturday' => 21
            ]
        ];

        $afternoonRanges = [
            [
                'name' => '13:00 - 16:00',
                'start' => '13:00',
                'start_delayed' => '13:30',
                'end' => '16:00',
                'rows' => 18,
                'rows_saturday' => 21
            ],
            [
                'name' => '14:00 - 17:00',
                'start' => '14:00',
                'start_delayed' => '14:30',
                'end' => '17:00',
                'rows' => 18,
                'rows_saturday' => 19
            ],
            [
                'name' => '15:00 - 18:00',
                'start' => '15:00',
                'start_delayed' => '15:30',
                'end' => '18:00',
                'rows' => 22,
                'rows_saturday' => 24,
            ],
            [
                'name' => '16:00 - 19:00',
                'start' => '16:00',
                'start_delayed' => '16:30',
                'end' => '19:00',
                'rows' => 22,
                'rows_saturday' => 24
            ],
            [
                'name' => '17:00 - 20:00',
                'start' => '17:00',
                'start_delayed' => '17:00',
                'end' => '20:00',
                'rows' => 23,
                'rows_saturday' => 21
            ],
            [
                'name' => '17:30 - 20:30',
                'start' => '17:30',
                'start_delayed' => '17:30',
                'end' => '20:30',
                'rows' => 22,
                'rows_saturday' => 21
            ],
            [
                'name' => '18:00 - 21:00',
                'start' => '18:00',
                'start_delayed' => '18:30',
                'end' => '21:00',
                'rows' => 23,
                'rows_saturday' => 25
            ],
        ];

        if ($date->dayOfWeek === 0) {
            return [
                [
                    'name' => '09:30 - 12:30',
                    'start' => '09:30',
                    'start_delayed' => '09:30',
                    'end' => '12:30',
                    'rows_sunday' => 22
                ],
                ...$morningRanges,
                [
                    'name' => '12:00 - 15:00',
                    'start' => '12:00',
                    'start_delayed' => '12:30',
                    'end' => '15:00',
                    'rows_sunday' => 23
                ],
                [
                    'name' => '13:00 - 15:00',
                    'start' => '13:00',
                    'start_delayed' => '13:00',
                    'end' => '15:00',
                    'rows_sunday' => 21
                ]
            ];
        }

        if ($date->dayOfWeek === 6) {
            return [
                [
                    'name' => '09:30 - 12:30',
                    'start' => '09:30',
                    'start_delayed' => '09:30',
                    'end' => '12:30',
                    'rows' => 23,
                    'rows_saturday' => 21
                ],
                ...$morningRanges,
                [
                    'name' => '12:00 - 15:00',
                    'start' => '12:00',
                    'start_delayed' => '12:30',
                    'end' => '15:00',
                    'rows' => 33,
                    'rows_saturday' => 25
                ],
                ...$afternoonRanges,
                [
                    'name' => '19:00 - 22:00',
                    'start' => '19:00',
                    'start_delayed' => '19:00',
                    'end' => '22:00',
                    'rows' => 23,
                    'rows_saturday' => 23
                ],
                [
                    'name' => '20:00 - 22:00',
                    'start' => '20:00',
                    'start_delayed' => '20:00',
                    'end' => '22:00',
                    'rows' => 23,
                    'rows_saturday' => 23
                ],
            ];
        }

        return [
            ...$morningRanges,
            [
                'name' => '12:00 - 15:00',
                'start' => '12:00',
                'start_delayed' => '12:30',
                'end' => '15:00',
                'rows' => 31,
                'rows_saturday' => 25
            ],
            ...$afternoonRanges,
            [
                'name' => '19:00 - 21:00',
                'start' => '19:00',
                'start_delayed' => '19:00',
                'end' => '21:00',
                'rows' => 22
            ],
        ];
    }

    /**
     * @param string $date
     * @return array|string[][]
     */
    public static function dateTreatmentSchedules(string $date): array
    {
        $date = Carbon::parse($date);

        $morningRanges = [
            [
                'name' => '09:00 - 09:30',
                'start' => '09:00',
                'start_delayed' => '09:00',
                'end' => '09:30',
                'rows' => 23
            ],
            [
                'name' => '09:30 - 10:00',
                'start' => '09:30',
                'start_delayed' => '09:30',
                'end' => '10:00',
                'rows' => 23
            ],
            [
                'name' => '10:00 - 10:30',
                'start' => '10:00',
                'start_delayed' => '10:00',
                'end' => '10:30',
                'rows' => 23
            ],
            [
                'name' => '10:30 - 11:00',
                'start' => '10:30',
                'start_delayed' => '10:30',
                'end' => '11:00',
                'rows' => 23
            ],
            [
                'name' => '11:00 - 11:30',
                'start' => '11:00',
                'start_delayed' => '11:00',
                'end' => '11:30',
                'rows' => 23
            ],
            [
                'name' => '11:30 - 12:00',
                'start' => '11:30',
                'start_delayed' => '11:30',
                'end' => '12:00',
                'rows' => 33
            ],
            [
                'name' => '12:00 - 12:30',
                'start' => '12:00',
                'start_delayed' => '12:00',
                'end' => '12:30',
                'rows' => 33
            ],
            [
                'name' => '12:30 - 13:00',
                'start' => '12:30',
                'start_delayed' => '12:30',
                'end' => '13:00',
                'rows' => 33
            ],
            [
                'name' => '13:00 - 13:30',
                'start' => '13:00',
                'start_delayed' => '13:00',
                'end' => '13:30',
                'rows' => 33
            ],
            [
                'name' => '13:30 - 14:00',
                'start' => '13:30',
                'start_delayed' => '13:30',
                'end' => '14:00',
                'rows' => 33
            ],
            [
                'name' => '14:00 - 14:30',
                'start' => '14:00',
                'start_delayed' => '14:00',
                'end' => '14:30',
                'rows' => 33
            ],
            [
                'name' => '14:30 - 15:00',
                'start' => '14:30',
                'start_delayed' => '14:30',
                'end' => '15:00',
                'rows' => 33
            ],
            [
                'name' => '15:00 - 15:30',
                'start' => '15:00',
                'start_delayed' => '15:00',
                'end' => '15:30',
                'rows' => 33
            ],
            [
                'name' => '15:30 - 16:00',
                'start' => '15:30',
                'start_delayed' => '15:30',
                'end' => '16:00',
                'rows' => 33
            ],
        ];

        $afternoonRanges = [
            [
                'name' => '16:00 - 16:30',
                'start' => '16:00',
                'start_delayed' => '16:00',
                'end' => '16:30',
                'rows' => 18
            ],
            [
                'name' => '16:30 - 17:00',
                'start' => '16:30',
                'start_delayed' => '16:30',
                'end' => '17:00',
                'rows' => 18
            ],
            [
                'name' => '17:00 - 17:30',
                'start' => '17:00',
                'start_delayed' => '17:00',
                'end' => '17:30',
                'rows' => 23
            ],
            [
                'name' => '17:30 - 18:00',
                'start' => '17:30',
                'start_delayed' => '17:30',
                'end' => '19:00',
                'rows' => 23
            ],
            [
                'name' => '18:00 - 18:30',
                'start' => '18:00',
                'start_delayed' => '18:00',
                'end' => '18:30',
                'rows' => 27
            ],
            [
                'name' => '18:30 - 19:00',
                'start' => '18:30',
                'start_delayed' => '18:30',
                'end' => '19:00',
                'rows' => 23
            ],
            [
                'name' => '19:00 - 19:30',
                'start' => '19:00',
                'start_delayed' => '19:00',
                'end' => '19:30',
                'rows' => 23
            ],
            [
                'name' => '19:30 - 20:00',
                'start' => '19:30',
                'start_delayed' => '19:30',
                'end' => '20:00',
                'rows' => 23
            ],
            [
                'name' => '20:00 - 20:30',
                'start' => '20:00',
                'start_delayed' => '20:00',
                'end' => '20:30',
                'rows' => 23
            ],
            [
                'name' => '20:30 - 21:00',
                'start' => '20:30',
                'start_delayed' => '20:30',
                'end' => '21:00',
                'rows' => 23
            ],
            [
                'name' => '21:00 - 21:30',
                'start' => '21:00',
                'start_delayed' => '21:00',
                'end' => '21:30',
                'rows' => 23
            ],
        ];

        if ($date->dayOfWeek === 0) {
            return [
                ...$morningRanges,
            ];
        }

        if ($date->dayOfWeek === 6) {
            return [
                ...$morningRanges,
                ...$afternoonRanges,
                [
                    'name' => '21:30 - 22:00',
                    'start' => '21:30',
                    'start_delayed' => '21:30',
                    'end' => '22:00',
                    'rows' => 23
                ],
            ];
        }

        return [
            ...$morningRanges,
            ...$afternoonRanges,
        ];
    }

    /**
     * @param int $month
     * @return string
     */
    public static function spanishMonthName(int $month): string
    {
        return match ($month) {
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre',
            default => ''
        };
    }

    /**
     * @param $day
     * @return string
     */
    public static function spanishWeekDay($day): string
    {
        return match ($day) {
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
            0 => 'Domingo',
            default => ''
        };
    }
}
