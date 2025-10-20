<?php

namespace App\Http\Controllers\Base\Discount;

use App\Enums\{
    CrudAction,
    OrderStatuses,
    PaymentStatuses,
};
use App\Http\Controllers\Common\GenericCrudProvider;
use App\Http\Requests\Base\Discount\{
    PostRequest,
    PutRequest,
};
use App\Models\Base\Discount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DiscountController extends GenericCrudProvider
{

    protected string $modelClass = Discount::class;

    protected string $indexView = 'base.discounts.index';
    protected string $showView = 'base.discounts.show';
    protected string $createView = 'base.discounts.create';
    protected string $editView = 'base.discounts.edit';

    protected function rules(): array
    {
        return [
            CrudAction::CREATE->value => (new PostRequest())->rules(),
            CrudAction::UPDATE->value => (new PutRequest())->rules(),
        ];
    }

    protected function afterCreate(Model $model, Request $request): ?array
    {
        $model->orderMarketplace->increment('discount', $model->amount);

        $totalPayment = $model->orderMarketplace
            ->paymentOrderMarketplace()
            ->where('status', PaymentStatuses::APPROVED->value)
            ->sum('payment');

        if ($totalPayment >= $model->orderMarketplace->amount_total) {
            $model->orderMarketplace->update([
                'status' => OrderStatuses::PAY->value,
            ]);
        }

        return null;
    }

    protected function afterDelete(Model $model)
    {
        $model->orderMarketplace->decrement('discount', $model->amount);

        $totalPayment = $model->orderMarketplace
            ->paymentOrderMarketplace()
            ->where('status', PaymentStatuses::APPROVED->value)
            ->sum('payment');

        if ($totalPayment >= $model->orderMarketplace->amount_total) {
            $model->orderMarketplace->update([
                'status' => OrderStatuses::PAY->value,
            ]);
        } else if ($model->orderMarketplace->status == OrderStatuses::PAY->value) {
            $model->orderMarketplace->update([
                'status' => OrderStatuses::TO_VALIDATE->value,
            ]);
        }
    }

    protected function getIndexViewName()
    {
        if (request()->inertia() && in_array(request()->route()->getName(), ['base.discounts.create', 'base.discounts.edit'])) {
            return null; // Force redirect back
        }

        return parent::getIndexViewName();
    }
}
