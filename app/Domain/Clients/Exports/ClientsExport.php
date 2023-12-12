<?php

namespace Domain\Clients\Exports;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ClientsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    public function __construct(private readonly Collection $records)
    {
    }

    public function collection(): Collection
    {
        return $this->records->transform(function ($item) {
            $lopd = $item->lopd_agree;
            if (empty($lopd)) {
                $lopd = 'Pendiente';
            } else {
                $lopd = $lopd === 1 ? 'Firmada' : 'No datos';
            }

            return [
                $item->email,
                $item->document,
                $item->name,
                $item->phone,
                $item->birthdate ? Carbon::parse($item->birthdate)->format('d/m/Y') : null,
                $item->address,
                $item->postcode,
                $lopd,
                $item->locality ? $item->locality->singular_entity_name : null,
                $item->locality && $item->locality->province ? $item->locality->province->name : null,
                Carbon::parse($item->created_at)->format('d/m/Y H:i'),
                $item->updated_at ? Carbon::parse($item->updated_at)->format('d/m/Y H:i') : null,
            ];
        });
    }

    public function headings(): array
    {
        return [
            ['Email', 'Identificación', 'Nombre', 'Teléfono', 'Cumpleaños', 'Dirección', 'Código Postal', 'LOPD', 'Localidad', 'Provincia', 'Creado', 'Última Modificación'],
        ];
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $workSheet = $event->sheet->getDelegate();
                $workSheet->getRowDimension('1')->setRowHeight(23);
                $workSheet->freezePane('A2');

                $cellRange = 'A1:M1';
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
                $styleArray = [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                            'color' => ['argb' => '0A427D'],
                        ],
                    ],
                ];

                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);
            },
        ];
    }
}
