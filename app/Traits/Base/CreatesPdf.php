<?php

namespace App\Traits\Base;

use Dompdf\Dompdf;
use Illuminate\Support\Facades\View;

trait CreatesPdf
{
    public function createPdfFile(string $viewName, array $data = [], $options = [], bool $render = true)
    {
        $pdf = new Dompdf($options);
        $pdf->loadHtml(View::make($viewName, $data)->render());

        if ($render) {
            $pdf->setPaper('letter', 'portrait');
            $pdf->render();
        }

        return $pdf;
    }
}
