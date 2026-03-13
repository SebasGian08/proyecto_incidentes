<?php

namespace BolsaTrabajo;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class StockCriticoExport implements
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
            'CALL sp_excel_sin_rotacion(?, ?)',
            [$this->inicio, $this->fin]
        ));
    }

    public function headings(): array
    {
        return [
            'Código Producto',
            'Descripción Producto',
            'Stock',
            'Stock Mínimo',
            'Precio Compra',
            'Precio Venta',
            'Días sin rotación'
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

                // Stock y stock mínimo
                $sheet->getStyle("C2:D{$lastRow}")
                    ->getNumberFormat()
                    ->setFormatCode(NumberFormat::FORMAT_NUMBER);

                // Precios
                $sheet->getStyle("E2:F{$lastRow}")
                    ->getNumberFormat()
                    ->setFormatCode('"S/ " #,##0.00');

                // Días sin rotación
                $sheet->getStyle("G2:G{$lastRow}")
                    ->getNumberFormat()
                    ->setFormatCode(NumberFormat::FORMAT_NUMBER);
            }
        ];
    }
}
