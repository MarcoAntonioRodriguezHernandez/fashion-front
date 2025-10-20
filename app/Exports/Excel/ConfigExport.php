<?php

namespace App\Exports\Excel;

use PhpOffice\PhpSpreadsheet\{
    IOFactory,
    Worksheet\Drawing,
    Worksheet\Worksheet
};

class ConfigExport
{
    public array $drawing = [];
    public array $stylesCells = [];
    public int $rowHeight = 30;
    public int $autoSize = 50;
    public int $qualityImage = 70;
    public int $widthImage = 300;
    public int $heightImage = 300;

    public function setStylesCells($cell, array $style = null)
    {
        $this->stylesCells[] = [
            $cell => $style
        ];
    }

    public function getStylesForImageCell($pathFile)
    {
        $spreadsheet = IOFactory::load($pathFile);
        $sheet = $spreadsheet->getActiveSheet();

        foreach ($sheet->getDrawingCollection() as $drawing) {
            if ($drawing instanceof Drawing) {
                $this->centerImageInCell($drawing, $sheet);
            }
        }
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($pathFile);
    }

    function centerImageInCell(Drawing $drawing, Worksheet $sheet)
    {
        $coordinates = $drawing->getCoordinates();
        $cell = $sheet->getCell($coordinates);
        $cellColumn = $cell->getColumn();
        $cellRow = $cell->getRow();

        $columnDimension = $sheet->getColumnDimension($cellColumn);
        $rowDimension = $sheet->getRowDimension($cellRow);

        $columnWidth = $columnDimension->getWidth();
        $rowHeight = $rowDimension->getRowHeight();
        $imageWidth = $drawing->getWidth();
        $imageHeight = $drawing->getHeight();
        $offsetX = ($columnWidth * 7.2 - $imageWidth) / 2;
        $offsetY = ($rowHeight * 1.3 - $imageHeight) / 2;

        $drawing->setOffsetX($offsetX);
        $drawing->setOffsetY($offsetY);
    }
}
