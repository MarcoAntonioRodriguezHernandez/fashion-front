<?php

namespace App\Exports\Excel;

use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    WithStyles,
    WithDrawings
};
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\{
    Alignment,
    Border
};
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Illuminate\Support\Collection;
use App\Exports\Excel\ConfigExport;

class BaseExport implements FromCollection, WithHeadings, WithStyles, WithDrawings, WithTitle
{

    protected array $headings;
    protected Collection $transformedData;
    public array $columnStyles;
    public ConfigExport $config;

    protected string $sheetTitle = 'Worksheet';

    public function __construct($data, $headers, ConfigExport|null $config)
    {
        $this->transformedData = $data;
        $this->headings = $headers;
        $this->config = $config ?? new ConfigExport;
        $this->columnStyles = $this->columnStyles();
    }

    public function collection()
    {
        return $this->transformedData;
    }

    public function headings(): array
    {
        return $this->headings;
    }

    public function drawings()
    {
        return $this->config->drawing;
    }

    public function styles(Worksheet $sheet)
    {
        $rowCount = count($this->transformedData) + 1;
        $lastColumn = Coordinate::stringFromColumnIndex(count($this->headings));

        for ($i = 2; $i <= $rowCount; $i++) {
            $sheet->getRowDimension($i)->setRowHeight($this->config->rowHeight);
        }

        foreach (range('A', $lastColumn) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize($this->config->autoSize);
        }

        $sheet->getStyle('A2:' . $lastColumn . $rowCount)->applyFromArray($this->columnStyles);

        $sheet->getStyle('A1:' . $lastColumn . '1')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
            'wrapText' => true,
        ]);

        foreach ($this->config->stylesCells as $cell => $style) {
            $sheet->getStyle($cell)->applyFromArray($style);
        }
    }

    public function columnStyles(): array
    {
        return [
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
            'font' => [
                'name' => 'Arial',
                'size' => 11,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
            'wrapText' => true,
        ];
    }

    public function setSheetTitle(string $title): self
    {
        $this->sheetTitle = $title;
        return $this;
    }

    public function title(): string
    {
        return $this->sheetTitle;
    }
}
