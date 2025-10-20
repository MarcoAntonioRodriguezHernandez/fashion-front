<?php

namespace App\Traits\Base\Supply;

use App\Enums\SupplyStatuses;
use App\Models\Base\SupplyTransfer;
use Illuminate\Support\Collection;

trait FiltersSupplies
{
    public function filterSuppliesBy(object $filters, int|bool $pageSize = null)
    {
        $query = SupplyTransfer::query()
            ->select('supply_transfers.*')
            ->with([
                'supply:id,code,status',
                'origin:id,name',
                'destination:id,name',
            ])
            ->when($filters->origin_id ?? false, fn($q) => $q->where('origin_id', $filters->origin_id))
            ->when($filters->destination_id ?? false, fn($q) => $q->where('destination_id', $filters->destination_id))
            ->when(
                $filters->active_only ?? false,
                fn($q) => $q->whereHas('suppliedItems', fn($q) => $q->where('delivered', false))
            );

        if ($pageSize === false) {
            return $query->get();
        }

        $pageSize = $pageSize ?: 15;
        return $query->paginate($pageSize)->through(fn(SupplyTransfer $st) => $this->buildSupplyData($st));
    }

    protected function buildSupplyData(SupplyTransfer $supplyTransfer): array
    {
        $supply = $supplyTransfer->supply;

        return [
            'id'           => (int) $supply?->id,
            'transfer_id'  => (int) $supplyTransfer->id,
            'code'         => (string) $supply?->code,

            'origin' => [
                'id'   => (int) $supplyTransfer->origin_id,
                'name' => (string) ($supplyTransfer->origin?->name ?? '—'),
            ],
            'destination' => [
                'id'   => (int) $supplyTransfer->destination_id,
                'name' => (string) ($supplyTransfer->destination?->name ?? '—'),
            ],

            'items_count'  => (int) $supplyTransfer->suppliedItems()->count(),

            'is_delivered' => (bool) $supplyTransfer->is_delivered,

            'status' => [
                'name'  => SupplyStatuses::getName((int) $supply?->status),
                'color' => SupplyStatuses::getColor((int) $supply?->status),
                'raw'   => (int) $supply?->status,
            ],
        ];
    }
}
