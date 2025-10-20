<?php

namespace App\Http\Controllers\Base\PricingScheme;

use App\Enums\CrudAction;
use App\Http\Controllers\Common\GenericCrudProvider;
use App\Http\Requests\Base\PricingScheme\{
    PostRequest,
    PutRequest
};
use App\Models\Base\{
    PricingScheme,
    Category,
    Sku
};
use App\Traits\Helpers\HandlerFilesTrait;
use Illuminate\Database\Eloquent\Model;

class PricingShemeController extends GenericCrudProvider
{
    use HandlerFilesTrait;

    protected string $modelClass = PricingScheme::class;

    protected string $indexView = 'base.pricing_schemes.index';
    protected string $showView = 'base.pricing_schemes.show';
    protected string $createView = 'base.pricing_schemes.create';
    protected string $editView = 'base.pricing_schemes.edit';

    protected function rules(): array
    {
        return [
            CrudAction::CREATE->value => (new PostRequest())->rules(),
            CrudAction::UPDATE->value => (new PutRequest())->rules(),
        ];
    }

    protected function pushCreateView()
    {
        $skus = Sku::all();
        $categories = Category::all();

        return compact('skus','categories');
    }

    protected function pushEditView( Model $model)
    {
        $skus = Sku::all();
        $categories = Category::all();

        return compact('skus', 'categories');
    }
}
