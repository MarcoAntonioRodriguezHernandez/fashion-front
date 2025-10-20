<?php

namespace App\Exports\Excel;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MultiSheetExport implements WithMultipleSheets
{
    protected array $sheets;

    public function __construct(array $sheetData, ConfigExport $config)
    {
        $this->sheets = [];

        foreach ($sheetData as $sheetName => $collection) {
            if ($collection->isEmpty()) {
                continue;
            }

            $headers = array_keys((array) $collection->first());

            $this->sheets[] = (new BaseExport($collection, $headers, $config))
                ->setSheetTitle($sheetName);
        }
    }

    public function sheets(): array
    {
        return $this->sheets;
    }
}
