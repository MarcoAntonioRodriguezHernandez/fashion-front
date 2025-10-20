<?php

namespace App\Http\Controllers\Base\Marketplace;

use App\Enums\CrudAction;
use App\Http\Controllers\Common\GenericCrudProvider;
use App\Http\Requests\Base\Marketplace\PutRequest;
use App\Models\Base\Marketplace;
use App\Traits\Helpers\HandlerFilesTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use InvalidArgumentException;

class MarketplaceController extends GenericCrudProvider
{
    // Common config
    use HandlerFilesTrait;

    protected string $modelClass = Marketplace::class;

    protected string $indexView = 'base.marketplace.index';
    protected string $showView = 'base.marketplace.show';
    protected string $createView = 'base.marketplace.create';
    protected string $editView = 'base.marketplace.edit';

    protected function rules(): array
    {
        return [
            CrudAction::UPDATE->value => (new PutRequest())->rules(),
        ];
    }

    protected function createRecord(Request $request)
    {
        throw new InvalidArgumentException('Creation of Marketplace is not allowed');
    }

    protected function beforeUpdate(array &$validatedData, Model $model, Request $request): ?array
    {
        if ($request->name)
            return ['slug' => Str::slug($request->name)];

        return [];
    }

    protected function deleteRecord($field)
    {
        throw new InvalidArgumentException('Deletion of Marketplace is not allowed');
    }
}