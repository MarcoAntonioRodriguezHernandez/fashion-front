<?php

namespace App\Http\Controllers\Base\Category;

use App\Enums\{
    CategoryStatuses,
    CrudAction,
};
use App\Http\Controllers\Common\GenericCrudProvider;
use App\Http\Requests\Base\Category\{
    PostRequest,
    PutRequest,
};
use App\Models\Base\{
    Category,
    Characteristic,
};
use App\Traits\Helpers\HandlerFilesTrait;
use Illuminate\Database\Eloquent\{
    Model,
    Builder,
};
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends GenericCrudProvider
{
    // Common config
    use HandlerFilesTrait;

    protected string $modelClass = Category::class;

    protected string $indexView = 'base.category.index';
    protected string $showView = 'base.category.show';
    protected string $createView = 'base.category.create';
    protected string $editView = 'base.category.edit';

    protected function rules(): array
    {
        return [
            CrudAction::CREATE->value => (new PostRequest())->rules(),
            CrudAction::UPDATE->value => (new PutRequest())->rules(),
        ];
    }

    protected function pushCreateView()
    {
        $categories = Category::all();
        $characteristics = Characteristic::onlyParents()->get();

        return compact('categories', 'characteristics');
    }

    protected function pushEditView(Model $model)
    {
        $categories = Category::all();
        $characteristics = Characteristic::onlyParents()->get();

        return compact('categories', 'characteristics');
    }

    protected function beforeReadAll(Builder $query, Request $request): Builder
    {
        return $query->where('status', '!=', CategoryStatuses::INACTIVE);
    }

    protected function beforeCreate(array &$validatedData, Request $request): ?array
    {
        return ['slug' => Str::slug($request->name)];
    }

    protected function afterCreate(Model $model, Request $request): ?array
    {
        $model->characteristics()->sync($request->characteristics);

        return null;
    }

    protected function beforeUpdate(array &$validatedData, Model $model, Request $request): ?array
    {
        if ($request->name)
            return ['slug' => Str::slug($request->name)];
        return null;
    }

    protected function afterUpdate(Model $model, Request $request): ?array
    {
        $model->characteristics()->sync($request->characteristics);

        return null;
    }
}
