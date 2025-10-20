<?php

namespace App\Services\Marketplace;

use App\Models\Base\PaymentType;
use App\Models\Marketplace\PaymentOrderMarketplace;
use App\Traits\Base\CreatesExcel;
use App\Traits\Marketplace\Order\FiltersIncomes;
use Illuminate\Support\Collection;

class IncomeReportService
{
    use FiltersIncomes, CreatesExcel;
    protected Collection $paymentTypesList;

    public function __construct()
    {
        $this->initializeCreatesExcel();
        $this->paymentTypesList = PaymentType::visible()->get()->push(PaymentType::where('slug', 'credito-de-cliente')->first());
    }

    public function createIncomeReportFile(object $filters, string $filename)
    {
        $rawData = $this->filterIncomesBy($filters, false)->flatten(1);

        $barly = collect();
        $otros = collect();

        foreach ($rawData as $payment) {
            $designerId = $payment['DesignerSlug'];
            unset($payment['DesignerSlug']);

            if ($designerId == 'barly') {
                $barly->push($payment);
            } else {
                $otros->push($payment);
            }
        }
                
       if ($barly->isNotEmpty()) {
            $barly->push($this->calculateTotalsRow($barly));
        }
        if ($otros->isNotEmpty()) {
            $otros->push($this->calculateTotalsRow($otros));
        }

        $exportSheets = [];
        if ($otros->isNotEmpty()) $exportSheets['Worksheet'] = $otros;
        if ($barly->isNotEmpty()) $exportSheets['Barly'] = $barly;
        if (empty($exportSheets)) return null;

        return $this->reportExportExcel($exportSheets, $filename);
    }

    
    protected function normalizeData(Collection $data, int|bool $pageSize = null)
    {
        return $data
            ->when($pageSize === false, fn($c) => $c->map(fn(PaymentOrderMarketplace $o) => $this->buildFormatExcelIncome($o)))
            ->when($pageSize !== false, fn($c) => $c->paginate($pageSize)->through(fn(PaymentOrderMarketplace $o) => $this->buildPaymentData($o)));
    }
    private function buildFormatExcelIncome(PaymentOrderMarketplace $payment)
    {
        $order = $payment->orderMarketplace;
        $items = $order->itemOrders;

        $surchargeAmount = $payment->reason == 1 ? $payment->payment : 0;
        $discountAmount = $order->discounts->where('applies_to', 0)->sum('amount');
        $waiverAmount = $order->discounts->where('applies_to', 1)->sum('amount');
        $totalItemsAmount = $items->sum(fn($item) => $item->item_price ?? 0);

        $lastPaymentDate = $order->paymentOrderMarketplace->first()?->created_at?->format('Y-m-d') ?? '-';
        $data = [];

        foreach ($items as $item) {
            $itemAmount = $item->item_price ?? 0;
            $proportion = $totalItemsAmount > 0 ? $itemAmount / $totalItemsAmount : 0;
            $proportionalPayment = round($payment->payment * $proportion, 2);
            $proportionalDiscount = round($discountAmount * $proportion, 2);
            $proportionalWaiver = round($waiverAmount * $proportion, 2);
            $proportionalSurcharge = round($surchargeAmount * $proportion, 2);

            $paymentTypes = [];
            foreach ($this->paymentTypesList as $type) {
                $paymentTypes[$type->name] = ($type->id === $payment->payment_type_id && $surchargeAmount == 0)
                    ? $proportionalPayment
                    : '-';
            }
            $itemVariant = $item->item->variant;
            $itemProduct = $item->item->product;

            $data[] = [
                'Solicitud' => $payment->created_at->format('Y-m-d'),
                'Sucursal' => $order->store->name ?? '-',
                '# Orden' => $order->code,
                'Descuento' => $proportionalDiscount ?: '-',
                'Condonacion' => $proportionalWaiver ?: '-',
                'Último pago' => $lastPaymentDate,
                'Tipo' => strtoupper($item->sale_name),
                'Vendedor' => $order->employee->full_name ?? '-', // 1
                'Título Producto' => $itemProduct->title ?? '-',
                'Nombre Producto' => $itemProduct->name ?? '-',
                'DesignerSlug' => $itemProduct->designer->slug,
                'Talla' => $itemVariant->size->full_name ?? '-',
                'Color' => $itemVariant->color->name ?? '-',
                'Costo Unitario' => $itemAmount,
                'Envío' => $order->orderShippingMarketplace?->shippingPrice->price ?: '-', //1
                'Seguro' => $item->rentDetailsMarketplace?->insurance_price ?: '-', // 6
                'Recargos' => $proportionalSurcharge ?? '-',
                ...$paymentTypes,
                'IVA' => round(($proportionalPayment / 1.16), 2),
                'Total Orden' => $order->amount_total,
            ];
        }

        return $data;
    }

    protected function calculateTotalsRow(Collection $data): array
    {
        if ($data->isEmpty()) {
            return [];
        }

        $paymentTypeNames = PaymentType::visible()
            ->get()
            ->push(PaymentType::where('slug', 'credito-de-cliente')->first())
            ->unique('id')
            ->pluck('name')
            ->toArray();

        $otherNumericColumns = ['Recargos', 'Envío', 'Seguro', 'IVA'];

        $columnsToSum = array_merge($paymentTypeNames, $otherNumericColumns);

        $totals = array_fill_keys($columnsToSum, 0);

        foreach ($data as $row) {
            foreach ($columnsToSum as $column) {
                if (isset($row[$column]) && is_numeric($row[$column])) {
                    $totals[$column] += $row[$column];
                }
            }
        }

        $totalsRow = array_fill_keys(array_keys($data->first()), '');

        $totalsRow['Solicitud'] = 'TOTALES';

        foreach ($totals as $column => $total) {
            $totalsRow[$column] = round($total, 2);
        }

        return $totalsRow;
    }
}
