<?php

namespace App\Http\Controllers\Base\Provider;

use App\Enums\CrudAction;
use App\Http\Controllers\Common\GenericCrudProvider;
use App\Http\Requests\Base\Provider\{
    PostRequest,
    PutRequest,
};
use App\Models\Base\{
    Country,
    Designer,
    Provider,
};
use App\Traits\Helpers\Support;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProviderController extends GenericCrudProvider
{
    use Support;

    protected string $modelClass = Provider::class;

    protected string $indexView = 'base.provider.index';
    protected string $showView = 'base.provider.show';
    protected string $createView = 'base.provider.create';
    protected string $editView = 'base.provider.edit';

    protected function rules(): array
    {
        return [
            CrudAction::CREATE->value => (new PostRequest())->rules(),
            CrudAction::UPDATE->value => (new PutRequest())->rules(),
        ];
    }

    protected function pushCreateView()
    {
        $designers = Designer::all();
        $countries = Country::all();

        return compact('designers', 'countries');
    }

    protected function afterCreate(Model $model, Request $request): ?array
    {
        $designerIds = $this->getModelIds(collect($request->designers), Designer::class);

        $model->designers()->sync($designerIds);

        return null;
    }

    protected function beforeCreate(array &$validatedData, Request $request): ?array
    {
        $request->merge([
            'slug' => Str::slug($request->name),
        ]);

        $request->validate([
            'slug' => 'required|string|unique:providers,slug',
        ]);

        return $request->all();
    }

    protected function pushEditView(Model $model)
    {
        $designers = Designer::all();
        $countries = Country::all();

        return compact('designers', 'countries');
    }

    protected function afterUpdate(Model $model, Request $request): ?array
    {
        $designerIds = $this->getModelIds(collect($request->designers), Designer::class);

        $model->designers()->sync($designerIds);

        return null;
    }

    protected function beforeUpdate(array &$validatedData, Model $model, Request $request): ?array
    {
        $request->merge([
            'slug' => Str::slug($request->name),
        ]);

        $request->validate([
            'slug' => 'required|string|unique:providers,slug,' . $model->id,
        ]);

        return $request->all();
    }
}
