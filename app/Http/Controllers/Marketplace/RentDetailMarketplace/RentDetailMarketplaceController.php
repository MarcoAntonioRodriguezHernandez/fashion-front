<?php

namespace App\Http\Controllers\Marketplace\RentDetailMarketplace;

use App\Enums\CrudAction;
use App\Http\Controllers\Common\GenericCrudProvider;
use App\Http\Requests\Marketplace\RentDetailMarketplace\{
    PostRequest, 
    PutRequest,
};
use App\Models\Marketplace\{
    ItemOrderMarketplace,
    RentDetailMarketplace,
};
use Illuminate\Database\Eloquent\Model;

class RentDetailMarketplaceController extends GenericCrudProvider
{

    protected string $modelClass = RentDetailMarketplace::class;

    //WEB VIEWS 
    protected string $indexView= 'marketplace.rent_detail_marketplace.index';
    protected string $createView= 'marketplace.rent_detail_marketplace.create';
    protected string $editView= 'marketplace.rent_detail_marketplace.edit';
    protected string $showView= 'marketplace.rent_detail_marketplace.show';

    protected function rules(): array
    {
        return [
            CrudAction::CREATE->value => (new PostRequest())->rules(),
            CrudAction::UPDATE->value => (new PutRequest())->rules(),
        ];
    }

    protected function pushCreateView()
    {
        $itemOrderMarketplace = ItemOrderMarketplace::all();
        $rentDetailMarketplace = RentDetailMarketplace::all();
        return compact('itemOrderMarketplace', 'rentDetailMarketplace');
    }

    protected function pushEditView(Model $model)
    {
        $itemOrderMarketplace = ItemOrderMarketplace::all();
        $rentDetailMarketplace = RentDetailMarketplace::all();
        return compact('itemOrderMarketplace', 'rentDetailMarketplace');
    }
}
