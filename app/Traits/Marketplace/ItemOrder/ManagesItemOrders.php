<?php

namespace App\Traits\Marketplace\ItemOrder;

use App\Enums\ItemStatuses;
use App\Models\Base\Item;
use App\Models\Marketplace\{
    ItemOrderMarketplace,
    RentDetailMarketplace,
};
use Carbon\Carbon;
use DateTime;
use RuntimeException;

trait ManagesItemOrders
{
    /**
     * Create a rent detail for the item order
     */
    protected function updateOrCreateRentDetail(array $data, ItemOrderMarketplace $itemOrder)
    {
        return $itemOrder->rentDetailsMarketplace()->updateOrCreate([
            'item_order_marketplace_id' => $itemOrder->id,
        ], [
            'date_start' => (new DateTime($data['date_start']))->setTime(0, 0, 0, 0),
            'date_end' => (new DateTime($data['date_end']))->setTime(0, 0, 0, 0),
            'insurance_price' => $data['insurance_price'],
            'status' => $data['status'],
        ]);
    }

    /**
     * Validate if the item is available for rent in the requested dates, or if it is available for sale
     */
    protected function validateItemTransaction(Item $item, array $requestedDates = null, RentDetailMarketplace $omitRent = null)
    {
        if (!$this->validateItemStatus($item)) {
            throw new RuntimeException('Item ' . $item->barcode . ' is not available for any transaction');
        }

        $reservedDates = $this->getReservedDates($item, $omitRent);

        if ($requestedDates) { // Check if item is available for rent in the requested dates
            $isReserved = $reservedDates->some(fn($d) => $d['startDate']->lte($requestedDates['endDate']) && $d['endDate']->gte($requestedDates['startDate']));

            if ($isReserved) {
                throw new RuntimeException('Item ' . $item->barcode . ' overlaps with other reservations on dates ' . $requestedDates['startDate']->format('d / m / Y') . ' to ' . $requestedDates['endDate']->format('d / m / Y'));
            }
        } else if ($reservedDates->isNotEmpty()) { // Check if item is available for sale (no future rents)
            throw new RuntimeException('Item ' . $item->barcode . ' is not available for sale');
        }

        return true;
    }

    /**
     * Get the future dates with a reservation for this item
     * 
     * Omit the rent detail if it is passed as an argument
     */
    protected function getReservedDates(Item $item, RentDetailMarketplace $omitRent = null)
    {
        return $item->rentDetailsMarketplace()
            ->whereDate('date_end', '>=', Carbon::now())
            ->where('item_order_marketplace.status', true)
            ->get()
            ->filter(fn($d) => $d->id !== $omitRent?->id)
            ->map(fn($d) => [
                'startDate' => $d->date_start,
                'endDate' => $d->date_end->addDays(3),
            ])
            ->values();
    }

    /**
     * Validate if the item is available for any transaction
     * 
     * @param Item $item
     * 
     * @return bool
     */
    public function validateItemStatus(Item $item)
    {
        return !in_array($item->status, [
            ItemStatuses::SOLD->value,
            ItemStatuses::ARCHIVED->value,
            ItemStatuses::LOST->value,
        ]);
    }
}
