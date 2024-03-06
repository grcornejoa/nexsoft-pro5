<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Reader\Xls\ConditionalFormatting;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\ConditionalFormatting\Wizard\TextValue;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

/**
 * Class ItemExport
 *
 * @package App\Exports
 */
class ItemExport extends DefaultValueBinder implements WithCustomValueBinder, WithColumnFormatting, FromView, ShouldAutoSize, WithColumnWidths
{
    use Exportable;

    public function records($records)
    {
        $this->records = $records;

        return $this;
    }

    public function bindValue(Cell $cell, $value)
    {
        $numericalColumns = ['C']; // columns with numerical values

        /*
        if (!in_array($cell->getColumn(), $numericalColumns) || $value == '' || $value == null) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);

            return true;
        }
*/

        if (in_array($cell->getColumn(), $numericalColumns) || $value == '' || $value == null) {
            $cell->setValueExplicit((float) $value, DataType::TYPE_STRING);

            return true;
        }



        // else return default behavior
        return parent::bindValue($cell, $value);
    }

    /**
     * @return array
     */

    public function columnFormats(): array
    {
        return [
            'M' => NumberFormat::FORMAT_DATE_DDMMYYYY

        ];
    }

    public function getExtraData(): array
    {
        return $this->extra_data;
    }

    /**
     * @param array $extra_data
     *
     * @return ItemExport
     */
    public function setExtraData(array $extra_data): ItemExport
    {
        $this->extra_data = $extra_data;
        return $this;
    }

    public function columnWidths(): array
    {
        return [
            'B' => 35,
            'C' => 35,
            'F' => 70,
            'M' => 20
        ];
    }



    public function view(): View
    {
        return view('tenant.items.exports.items', [
            'records' => $this->records,
            'extra_data' => $this->extra_data,
        ]);
    }
}
