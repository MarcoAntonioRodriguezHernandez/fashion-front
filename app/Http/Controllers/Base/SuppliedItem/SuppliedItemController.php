<?php

namespace App\Http\Controllers\Base\SuppliedItem;

use App\Http\Controllers\Common\GenericCrudProvider;
use App\Enums\CrudAction;
use App\Traits\Helpers\HandlerFilesTrait;
use App\Http\Requests\Base\SuppliedItem\{
    PostRequest,
    PutRequest
};
use App\Models\Base\{
    Item,
    SuppliedItem,
    SupplyTransfer
};
use Illuminate\Database\Eloquent\Model;


class SuppliedItemController extends GenericCrudProvider
{
    // Common config
    use HandlerFilesTrait;

    protected string $modelClass = SuppliedItem::class;

    protected string $indexView = 'base.supply_item.index';
    protected string $showView = 'base.supply_item.show';
    protected string $createView = 'base.supply_item.create';
    protected string $editView = 'base.supply_item.edit';

    protected function rules(): array
    {
        return [
            CrudAction::CREATE->value => (new PostRequest())->rules(),
            CrudAction::UPDATE->value => (new PutRequest())->rules(),
        ];
    }

    protected function pushCreateView()
    {
        $items = Item::all();
        $supply_transfers = SupplyTransfer::all();

        return compact('items', 'supply_transfers');
    }

    protected function pushEditView(Model $model)
    {
        $items = Item::all();
        $supply_transfers = SupplyTransfer::all();

        return compact('items', 'supply_transfers');
    }
}
