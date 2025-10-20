<?php

namespace App\Traits\Base;

use App\Exports\Excel\{
    BaseExport,
    ConfigExport,
    MultiSheetSpoutExport,
    MultiSheetExport
};
use App\Traits\Helpers\HandleImages;
use Exception;
use Illuminate\Support\{
    Collection,
    Str
};
use InvalidArgumentException;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Worksheet\{
    Drawing,
    MemoryDrawing,
};

trait CreatesExcel
{
    use HandleImages;
    /**
     * @var Collection
     */
    private Collection $data;

    public ConfigExport $config;

    public function initializeCreatesExcel()
    {
        $this->config = $this->makeConfig();
    }

    /**
     * @throws Exception
     */
    public function reportExportExcel(array|Collection $data, string $filename = null, string $folder = 'tmp')
    {
        $this->config = isset($this->config) ? $this->config : $this->makeConfig();

        $filename = ($filename ?? 'report_' . $this->getFilename()) . '.xlsx';
        $fullPath = 'reports/' . $folder . '/' . $filename;

        if (is_array($data) && !empty($data) && array_is_list($data) === false) {
            $export = new MultiSheetSpoutExport($data);
            $export->export(storage_path('app/' . $fullPath)); 
            // $export = new MultiSheetExport($data, $this->config);
            // Excel::store($export, $fullPath);
        } elseif ($data instanceof Collection) {
            if ($data->isEmpty()) {
                throw new InvalidArgumentException('At least one value expected');
            }
            $headers = array_keys((array) $data->first());
            $export = new BaseExport($data, $headers, $this->config);
            Excel::store($export, $fullPath);
        } else {
            throw new InvalidArgumentException('Invalid data format for Excel export');
        }

        return storage_path('app/' . $fullPath);
    }

    public function addDrawingObject(Drawing|string $object, string $column = 'A', int $row = null): MemoryDrawing|Drawing
    {
        $row ??= count($this->config->drawing) + 2;

        if (is_string($object)) {
            $object = $this->createDrawingObject($object, $this->config);
        }

        $object->setCoordinates($column . $row);

        $this->config->drawing[] = $object;

        return $object;
    }

    public function createDrawingObject(string $path, ConfigExport $config)
    {
        $image = $this->getAndResizeImage($path, $config->widthImage, null, $config->qualityImage);

        $tempImagePath = tempnam(sys_get_temp_dir(), 'drawing');
        file_put_contents($tempImagePath, $image);

        $drawing = new Drawing();
        $drawing->setPath($tempImagePath);
        $drawing->setHeight($config->rowHeight + 15);

        return $drawing;
    }

    protected function makeConfig(): ConfigExport
    {
        $config = new ConfigExport();

        $config->rowHeight = 15;

        return $config;
    }

    private function getFilename()
    {
        return date('Y-m-d_H-i-s') . '_' . Str::random(5);
    }
}
