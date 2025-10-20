<?php

namespace App\Http\Controllers\Marketplace\OrderMarketplace;

use App\Enums\OrderSaleType;
use App\Http\Controllers\Controller;
use App\Services\Marketplace\OrderReportService;
use App\Traits\{
    Marketplace\Order\FiltersOrders,
    Helpers\ResponseTrait,
    Base\CreatesExcel,
};
use App\Http\Requests\Marketplace\Order\FilterRequest;
use App\Models\Base\Store;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Exception;


class OrderMarketplaceReportController extends Controller
{
    use ResponseTrait, FiltersOrders;

    protected $orderReportService;

    public function __construct(OrderReportService $orderReportService)
    {
        $this->orderReportService = $orderReportService;
    }


    public function filterView(FilterRequest $request)
    {
        try {
            $orders = Inertia::lazy(function () use ($request) {
                return $this->filterOrdersBy((object) $request->all());
            });

            $stores = Store::select('id', 'name')->orderBy('name')->get();
            $saleTypes = OrderSaleType::getAllNames();
            $saleTypes[0] = 'Todos';

            return Inertia::render('OrderMarketplace/Filter', compact('orders', 'stores', 'saleTypes'));
        } catch (Exception $e) {
            return $this->makeResponse([
                'message' => 'Error while retrieving filter data',
                'success' => false,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'exception' => $e,
            ]);
        }
    }

    public function orderReportExcel(Request $request)
    {
        try {
            $filename = 'order-report-' . date('YmdHis');

            $path = $this->orderReportService->createOrderReportFile((object) $request->all(), $filename);

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

            return response()->download($fullPath, $resource)->deleteFileAfterSend(true);
        } catch (Exception $e) {
            abort(404);
        }
    }
}
