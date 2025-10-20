<?php

namespace App\Http\Controllers\Base\Store;

use App\Enums\{
    CrudAction,
    StoreStatuses
};
use App\Http\Controllers\Common\GenericCrudProvider;
use App\Http\Requests\Base\Store\{
    PostRequest,
    PutRequest,
};
use App\Models\Base\{
    Marketplace,
    Store,
};
use App\Traits\Helpers\{
    HandlerFilesTrait,
    Support,
};
use Illuminate\Database\Eloquent\{
    Model,
    Builder,
};
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StoreController extends GenericCrudProvider
{
    // Common config
    use HandlerFilesTrait, Support;

    protected string $modelClass = Store::class;

    protected string $indexView = 'base.store.index';
    protected string $showView = 'base.store.show';
    protected string $createView = 'base.store.create';
    protected string $editView = 'base.store.edit';

    protected ?string $searchField = 'name';

    protected function rules(): array
    {
        return [
            CrudAction::CREATE->value => (new PostRequest())->rules(),
            CrudAction::UPDATE->value => (new PutRequest())->rules(),
        ];
    }

    public function pushCreateView()
    {
        $marketplaces = Marketplace::all();
        return compact('marketplaces');
    }

    protected function pushEditView(Model $model)
    {
        $marketplaces = Marketplace::all();

        return compact('model', 'marketplaces');
    }
    protected function beforeReadAll(Builder $query, Request $request): Builder
    {
        return $query->where('status', '!=', StoreStatuses::INACTIVE);
    }

    protected function afterReadAll(Model $model): array
    {
        return [
            'locations' => $model->select('name as title', 'lat', 'long', 'address as description')->get()->toArray()
        ];
    }

    protected function beforeCreate(array &$validatedData, Request $request): ?array
    {
        $request->merge([
            'slug' => Str::slug($request->name),
        ]);

        $request->validate([
            'slug' => 'required|string|unique:stores,slug',
        ]);

        return $request->all();
    }

    protected function beforeUpdate(array &$validatedData, Model $model, Request $request): ?array
    {
        $request->merge([
            'slug' => Str::slug($request->name),
        ]);

        $request->validate([
            'slug' => 'required|string|unique:stores,slug,' . $model->id,
        ]);

        return $request->all();
    }
}