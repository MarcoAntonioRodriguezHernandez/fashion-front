<?php

namespace App\Exports\Excel;

use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\CellAlignment;
use OpenSpout\Writer\XLSX\Writer;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Common\Entity\Style\Border;
use OpenSpout\Common\Entity\Style\Color;
use OpenSpout\Common\Entity\Style\BorderPart;

class MultiSheetSpoutExport
{
    protected array $sheets;

    public function __construct(array $sheets)
    {
        $this->sheets = $sheets;
    }

    public function export(string $filePath)
    {
        $writer = new Writer();
        $writer->openToFile($filePath);

        $headerStyle = $this->getHeaderStyle();
        $bodyStyle = $this->getBodyStyle();

        foreach ($this->sheets as $sheetName => $data) {
            $writer->getCurrentSheet()->setName($sheetName);

            if (!empty($data) && !$data->isEmpty()) {
                $firstRow = is_array($data) ? ($data[0] ?? null) : $data->first();
                $headers = array_keys($firstRow);
                $headerRow = Row::fromValues($headers, $headerStyle);
                $writer->addRow($headerRow);

                foreach ($data as $row) {
                    $values = (array) $row;
                    $dataRow = Row::fromValues($values, $bodyStyle);
                    $writer->addRow($dataRow);
                }

                $this->autoSizeColumns($writer, $headers, $data);
            }

            if (next($this->sheets) !== false) {
                $writer->addNewSheetAndMakeItCurrent();
            }
        }

        $writer->close();
    }

    private function autoSizeColumns(Writer $writer, array $headers, $data)
    {
        // Initialize maxLengths with header lengths
        $maxLengths = [];
        foreach ($headers as $index => $header) {
            $maxLengths[$index] = mb_strlen($header);
        }

        // Iterate over the data to find the maximum length of each column
        foreach ($data as $row) {
            $values = (array) $row;
            foreach ($headers as $index => $header) {
                // Safely get the value for the current column using its header key
                $value = $values[$headers[$index]] ?? '';
                $length = mb_strlen((string) $value);
                
                // Update the maximum length if the current value is longer
                if ($length > $maxLengths[$index]) {
                    $maxLengths[$index] = $length;
                }
            }
        }

        // Apply the calculated column widths
        foreach ($maxLengths as $columnIndex => $length) {
            $writer->getCurrentSheet()->setColumnWidth($length + 3, $columnIndex + 1);
        }
    }

    private function getHeaderStyle(): Style
    {
        $headerStyle = (new Style());
        $headerStyle->setFontBold();
        $headerStyle->setCellAlignment(CellAlignment::CENTER);

        $border = (new Border(
            new BorderPart(Border::BOTTOM, Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID),
            new BorderPart(Border::LEFT, Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID),
            new BorderPart(Border::RIGHT, Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID),
            new BorderPart(Border::TOP, Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
        ));
            
        $headerStyle->setBorder($border);
        return $headerStyle;
    }

    private function getBodyStyle(): Style
    {
        $bodyStyle = (new Style());
        $bodyStyle->setFontSize(11);
        $bodyStyle->setFontName('Arial');
        $bodyStyle->setCellAlignment(CellAlignment::CENTER);
        $border = (new Border(
            new BorderPart(Border::BOTTOM, Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID),
            new BorderPart(Border::LEFT, Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID),
            new BorderPart(Border::RIGHT, Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID),
            new BorderPart(Border::TOP, Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
        ));
            
        $bodyStyle->setBorder($border);
        return $bodyStyle;
    }
}