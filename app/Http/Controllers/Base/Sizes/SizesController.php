<?php

namespace App\Http\Controllers\Base\Sizes;

use App\Enums\CrudAction;
use App\Http\Controllers\Common\GenericCrudProvider;
use App\Http\Requests\Base\Sizes\{
    PostRequest,
    PutRequest,
};
use App\Models\Base\{
    Characteristic,
    Size,
};
use App\Traits\Helpers\HandlerFilesTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SizesController extends GenericCrudProvider
{
    // Common config
    use HandlerFilesTrait;

    protected string $modelClass = Size::class;

    protected string $indexView = 'base.sizes.index';
    protected string $showView = 'base.sizes.show';
    protected string $createView = 'base.sizes.create';
    protected string $editView = 'base.sizes.edit';

    protected function rules(): array
    {
        return [
            CrudAction::CREATE->value => (new PostRequest())->rules(),
            CrudAction::UPDATE->value => (new PutRequest())->rules(),
        ];
    }

    protected function pushCreateView()
    {
        $characteristics = Characteristic::onlyParents()->with('children')->get();

        return compact('characteristics');
    }

    protected function pushEditView(Model $model)
    {
        $characteristics = Characteristic::onlyParents()->with('children')->get();

        return compact('characteristics');
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
    }

    protected function afterUpdate(Model $model, Request $request): ?array
    {
        $model->characteristics()->sync($request->characteristics);

        return null;
    }
}
