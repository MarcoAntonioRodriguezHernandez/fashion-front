<?php

namespace App\Http\Controllers\Base\ShippingPrice;

use App\Http\Controllers\Common\GenericCrudProvider;
use App\Enums\CrudAction;
use App\Http\Requests\Base\ShippingPrice\{
    PostRequest,
    PutRequest,
};
use App\Models\Base\ShippingPrice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ShippingPriceController extends GenericCrudProvider
{

    protected string $modelClass = ShippingPrice::class;

    protected string $indexView = 'base.shipping_prices.index';
    protected string $showView = 'base.shipping_prices.show';
    protected string $createView = 'base.shipping_prices.create';
    protected string $editView = 'base.shipping_prices.edit';

    protected function rules(): array
    {
        return [
            CrudAction::CREATE->value => (new PostRequest())->rules(),
            CrudAction::UPDATE->value => (new PutRequest())->rules(),
        ];
    }

    protected function beforeCreate(array &$validatedData, Request $request): ?array
    {
        return [
            'code' => strtoupper($validatedData['code']),
        ];
    }

    protected function beforeUpdate(array &$validatedData, Model $model, Request $request): ?array
    {
        return [
            'code' => strtoupper($validatedData['code']),
        ];
    }
}
