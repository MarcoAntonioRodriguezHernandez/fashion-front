<?php

namespace App\Http\Controllers\Base\Supply;

use App\Http\Controllers\Controller;
use App\Http\Requests\Base\Supply\FilterRequest;
use App\Models\Base\Store;
use App\Traits\Base\CreatesExcel;
use App\Traits\Base\Supply\{
    CreatesReport,
    FiltersSupplies,
};
use App\Traits\Helpers\ResponseTrait;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class SupplyReportController extends Controller
{
    use ResponseTrait, FiltersSupplies, CreatesReport, CreatesExcel;

    public function __construct()
    {
        $this->initializeCreatesExcel();
    }

    public function filterView(FilterRequest $request)
    {
        try {
            $suppliesResult = Inertia::lazy(function () use ($request) {
                return $this->filterSuppliesBy((object) $request->all());
            });

            $stores = Store::select('id', 'name')->orderBy('name')->get();

            return Inertia::render('Supply/Filter', compact('suppliesResult', 'stores'));
        } catch (ModelNotFoundException $e) {
            return $this->makeResponse([
                'message' => 'Item not found',
                'success' => false,
                'status' => Response::HTTP_NOT_FOUND,
                'exception' => $e,
            ]);
        } catch (Exception $e) {
            return $this->makeResponse([
                'message' => 'Error while retrieving filter data',
                'success' => false,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'exception' => $e,
            ]);
        }
    }

    public function reportDocument(FilterRequest $request)
    {
        try {
            $filename = 'supply_report_' . date('Y-m-d_H-i-s');

            $data = $this->filterSuppliesBy($request, false);
            $data = $this->buildTransfersData($data)
                ->map(fn($st) => $st->suppliedItems)
                ->flatten(1)
                ->map(fn($si) => [
                    'Origen' => $si->supplyTransfer->origin->name,
                    'Destino' => $si->supplyTransfer->destination->name,
                    'Distribución' => $si->supplyTransfer->supply->code,
                    'Título Producto' => $si->item->product->title,
                    'Nombre Producto' => $si->item->product->name,
                    'Código de Barras' => $si->item->barcode,
                    'Color' => $si->item->variant->color->name,
                    'Talla' => $si->item->variant->size->full_name,
                    'Notas' => Str::limit($si->details, 50) ?: 'Ninguna Nota',
                    'Estado' => Str::of($si->statusName)->when($si->integrity, fn($s) => $s . ': ' . $si->integrity_name),
                    '' => '',
                ]);

            $path = $this->reportExportExcel($data, $filename);

            $this->config->getStylesForImageCell($path);

            return response()->download($path, 'supply_report_' . date('Y-m-d_H-i-s') . '.xlsx')->deleteFileAfterSend(true);
        } catch (Exception $e) {
            return $this->makeResponse([
                'message' => 'Error generating inventory report',
                'success' => false,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'exception' => $e->getMessage(),
            ]);
        }
    }
}
