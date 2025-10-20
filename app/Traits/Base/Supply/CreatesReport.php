<?php

namespace App\Traits\Base\Supply;

use App\Models\Base\Supply;
use App\Traits\Base\CreatesPdf;
use Illuminate\Support\{
    Collection,
    Str,
};

trait CreatesReport
{
    use CreatesPdf {
        createPdfFile as genericPdfFile;
    }

    public function createReportFile(Supply|Collection $transfersList)
    {
        return $this->createPdfFile([
            'logoImage' => $this->getLogoImage(),
            'supplyTransfers' => $this->buildTransfersData($transfersList),
            'supply' => $transfersList instanceof Supply ? $transfersList : null,
        ]);
    }

    public function createPdfFile(array $data = [])
    {
        $pdfFile =  $this->genericPdfFile($this->getViewName(), $data, $this->getDefaultOptions(), false);
        $pdfFile->setPaper('legal', 'landscape');
        $pdfFile->render();

        return $pdfFile;
    }

    protected function buildTransfersData(Supply|Collection $transfersList)
    {
        if ($transfersList instanceof Supply)
            return $transfersList->supplyTransfers;

        return $transfersList
            ->mapToGroups(fn ($t) => [$t->origin_id . '-' . $t->destination_id => $t])
            ->map(function ($g) {
                return (object) [
                    'origin' => $g->first()->origin,
                    'destination' => $g->first()->destination,
                    'suppliedItems' => $g->map(fn ($t) => $t->suppliedItems)->flatten(),
                ];
            });
    }

    protected function getLogoImage()
    {
        $logoImage = public_path('media/logos/cmLogo.png');

        return 'data:image/jpg;base64,' . base64_encode(file_get_contents($logoImage));
    }

    protected function getDefaultOptions()
    {
        return [
            'isRemoteEnabled' => true,
        ];
    }

    protected function getViewName()
    {
        return 'base.supply.report';
    }

    protected function createLogId()
    {
        return Str::random(5);
    }
}
