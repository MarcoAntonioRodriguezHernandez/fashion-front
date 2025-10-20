<?php

namespace App\Http\Controllers\Marketplace\OrderMarketplace;

use App\Enums\OrderSaleType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Marketplace\Order\IncomeRequest;
use App\Services\Marketplace\IncomeReportService;
use App\Traits\Helpers\ResponseTrait;
use App\Models\Base\Store;
use App\Traits\Marketplace\Order\FiltersIncomes;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Exception;

class OrderMarketplaceIncomeController extends Controller
{
    use ResponseTrait, FiltersIncomes;

    protected $incomeReportService;

    public function __construct(IncomeReportService $incomeReportService)
    {
        $this->incomeReportService = $incomeReportService;
    }

    public function filterView(IncomeRequest $request)
    {
        try {
            $payments = Inertia::lazy(function () use ($request) {
                return $this->filterIncomesBy((object) $request->all());
            });
            
            $stores = Store::select('id', 'name')->orderBy('name')->get();
            $saleTypes = OrderSaleType::getAllNames();
            $saleTypes[0] = 'Todos';

            return Inertia::render('OrderMarketplace/IncomeFilter', compact('payments', 'stores', 'saleTypes'));
        } catch (Exception $e) {
            return $this->makeResponse([
                'message' => 'Error while retrieving filter data',
                'success' => false,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'exception' => $e,
            ]);
        }
    }

    public function incomeReportExcel(Request $request)
    {
        try {
            $filename = 'income-report-' . date('YmdHis');

            $path = $this->incomeReportService->createIncomeReportFile((object) $request->all(), $filename);

            return response()->download($path, $filename . '.xlsx')->deleteFileAfterSend(true);
        } catch (Exception $e) {
            return $this->makeResponse([
                'message' => 'Failed to create record',
                'success' => false,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'exception' => $e->getMessage(),
            ]);
        }
    }

    public function resource(string $resource)
    {
        try {
            $fullPath = storage_path('app/reports/tmp/' . $resource);

            return response()->download($fullPath, $resource)->deleteFileAfterSend(false);
        } catch (Exception $e) {
            abort(404);
        }
    }
}
