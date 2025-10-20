<?php

namespace App\Services\Marketplace;

use App\Models\Marketplace\OrderMarketplace;
use App\Traits\Base\CreatesExcel;
use App\Traits\Marketplace\Order\FiltersOrders;
use Illuminate\Support\Collection;

class OrderReportService
{
    use FiltersOrders, CreatesExcel;

    public function __construct()
    {
        $this->initializeCreatesExcel();
    }

    public function createOrderReportFile(object $filters, string $filename)
    {
        $data = $this->filterOrdersBy($filters, false)->flatten(1);

        return $this->reportExportExcel($data, $filename);
    }
    
    protected function normalizeData(Collection $data, int|bool $pageSize = null)
    {
        return $data
            ->when($pageSize === false, fn ($c) => $c->map(fn (OrderMarketplace $o) => $this->buildFormatExcelOrder($o)))
            ->when($pageSize !== false, fn ($c) => $c->paginate($pageSize)->through(fn (OrderMarketplace $o) => $this->buildOrderData($o)));
    }

    private function buildFormatExcelOrder(OrderMarketplace $order)
    {
        $items = $order->itemOrders()->get();
        $data = [];
        foreach ($items as $item) {
            $data[] = [
                'Solicitud' => $order->created_at->format('Y-m-d'),
                'Sucursal' => $order->store->name,
                '# Orden' => $order->code,
                'Tipo' => strtoupper($item->sale_name),
                'Vendedor' => $order->employee->full_name,
                'Título Producto' => $item->item->product->title,
                'Nombre Producto' => $item->item->product->name,
                'Envío' => strval($order->orderShippingMarketplace?->shippingPrice->price ?? '-'),
                'Talla' => $item->item->variant->size->full_name,
                'Color' => $item->item->variant->color->name,
                'IVA' => round($item->item->price_sale / 1.16, 2),
                'Total' => $item->item->price_sale,
                'Descuento' => $order->discount ?: '-',
            ];
        }

        return $data;
    }

}
