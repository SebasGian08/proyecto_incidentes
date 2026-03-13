<?php

namespace BolsaTrabajo;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class MargenProductoExport implements
    FromCollection,
    WithHeadings,
    WithEvents,
    ShouldAutoSize
{
    protected $inicio;
    protected $fin;

    public function __construct($inicio, $fin)
    {
        $this->inicio = $inicio;
        $this->fin = $fin;
    }

    public function collection()
    {
        return collect(DB::select(
            'CALL sp_excel_rentabilidad(?, ?)',
            [$this->inicio, $this->fin]
        ));
    }

    public function headings(): array
    {
        return [
            'Código Producto',
            'Producto',
            'Cantidad Vendida',
            'Ingreso Total',
            'Costo Total',
            'Margen Bruto'
        ];
    }

    public function registerEvents(): array
    {
        return [

            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();
                $lastCol = $sheet->getHighestColumn();

                /* =====================
                   FILTROS
                   ===================== */
                $sheet->setAutoFilter("A1:{$lastCol}{$lastRow}");

                /* =====================
                   CONGELAR ENCABEZADO
                   ===================== */
                $sheet->freezePane('A2');

                /* =====================
                   NEGRITA ENCABEZADOS
                   ===================== */
                $sheet->getStyle("A1:{$lastCol}1")
                    ->getFont()->setBold(true);

                /* =====================
                   FORMATOS NUMÉRICOS
                   ===================== */

                // Cantidad vendida
                $sheet->getStyle("C2:C{$lastRow}")
                    ->getNumberFormat()
                    ->setFormatCode(NumberFormat::FORMAT_NUMBER);

                // Ingreso, Costo y Margen
                $sheet->getStyle("D2:F{$lastRow}")
                    ->getNumberFormat()
                    ->setFormatCode('"S/ " #,##0.00');
            }
        ];
    }
}
