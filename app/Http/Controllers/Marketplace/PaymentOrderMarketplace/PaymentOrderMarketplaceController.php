<?php

namespace App\Http\Controllers\Marketplace\PaymentOrderMarketplace;

use App\Enums\{
    CrudAction,
    OrderStatuses,
    PaymentStatuses,
};
use App\Http\Controllers\Common\GenericCrudProvider;
use App\Http\Requests\Base\PaymentOrderMarketplace\{
    PostRequest,
    PutRequest,
};
use App\Models\Marketplace\{
    PaymentOrderMarketplace,
    OrderMarketplace,
};
use App\Models\Base\PaymentType;
use App\Traits\Helpers\HandlerFilesTrait;
use App\Traits\Marketplace\Order\ManagesOrders;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PaymentOrderMarketplaceController extends GenericCrudProvider
{

    // Common config
    use HandlerFilesTrait, ManagesOrders;

    protected string $modelClass = PaymentOrderMarketplace::class;

    protected string $indexView = 'marketplace.payment_order_marketplace.index';
    protected string $showView = 'marketplace.payment_order_marketplace.show';
    protected string $createView = 'marketplace.payment_order_marketplace.create';
    protected string $editView = 'marketplace.payment_order_marketplace.edit';

    protected function rules(): array
    {
        return [
            CrudAction::CREATE->value => (new PostRequest())->rules(),
            CrudAction::UPDATE->value => (new PutRequest())->rules(),
        ];
    }

    protected function pushCreateView()
    {
        $orderMarketplaces = OrderMarketplace::with('user')->get();
        $paymentTypes = PaymentType::visible()->orderBy('name')->get();

        return compact('orderMarketplaces', 'paymentTypes');
    }

    protected function pushEditView(Model $model)
    {
        $orderMarketplaces = OrderMarketplace::with('user')->get();
        $paymentTypes = PaymentType::all();

        return compact('orderMarketplaces', 'paymentTypes');
    }

    protected function afterCreate(Model $model, Request $request): ?array
    {
        if ($model->status != PaymentStatuses::APPROVED->value)
            return null;

        $totalPayment = $model->orderMarketplace
            ->paymentOrderMarketplace()
            ->where('status', PaymentStatuses::APPROVED->value)
            ->sum('payment');

        if ($totalPayment >= $model->orderMarketplace->amount_total) {
            $this->updateOrderStatus($model->orderMarketplace, OrderStatuses::PAY);
        }

        return null;
    }

    protected function createRecord(Request $request)
    {
        $validatedData = $request->validate($this->rules()[CrudAction::CREATE->value]);
        if (($validatedData['status'] ?? null) === PaymentStatuses::APPROVED->value) {
            $order = OrderMarketplace::where('id', $validatedData['order_marketplace_id'])->lockForUpdate()->firstOrFail();
            $totalPagado = $order->paymentOrderMarketplace()->where('status', PaymentStatuses::APPROVED->value)->sum('payment');
            $nuevoPago = $validatedData['payment'] ?? 0;
            $totalFinal = $totalPagado + $nuevoPago;
            if ($totalFinal > $order->amount_total) {
                return $this->makeResponse(['message' => "El pago excede el total del pedido. Pagado: $totalPagado, nuevo pago: $nuevoPago, total permitido: {$order->amount_total}",                     'success' => false,                     'status' => \Symfony\Component\HttpFoundation\Response::HTTP_UNPROCESSABLE_ENTITY,]);
            }
        }
        return parent::createRecord($request);
    }

    protected function afterUpdate(Model $model, Request $request): ?array
    {
        $totalPayment = $model->orderMarketplace
            ->paymentOrderMarketplace()
            ->where('status', PaymentStatuses::APPROVED->value)
            ->sum('payment');

        if ($totalPayment >= $model->orderMarketplace->amount_total) {
            $this->updateOrderStatus($model->orderMarketplace, OrderStatuses::PAY);
        } else if ($model->orderMarketplace->status == OrderStatuses::PAY->value) {
            $this->updateOrderStatus($model->orderMarketplace, OrderStatuses::TO_VALIDATE);
        }

        return null;
    }

    protected function getIndexViewName()
    {
        if (request()->inertia() && in_array(request()->route()->getName(), ['payment_order_marketplace.create', 'payment_order_marketplace.edit'])) {
            return null; // Force redirect back
        }

        return parent::getIndexViewName();
    }
}
