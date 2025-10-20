<?php

namespace App\Http\Controllers\Base\Characteristics;

use App\Enums\CrudAction;
use App\Http\Controllers\Common\GenericCrudProvider;
use App\Http\Requests\Base\Characteristics\{
    PostRequest,
    PutRequest,
};
use App\Models\Base\Characteristic;
use App\Traits\Helpers\HandlerFilesTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class CharacteristicController extends GenericCrudProvider
{
    //
    // Common config
    use HandlerFilesTrait;

    protected string $modelClass = Characteristic::class;

    protected string $indexView = 'base.characteristics.index';
    protected string $showView = 'base.characteristics.show';
    protected string $createView = 'base.characteristics.create';
    protected string $editView = 'base.characteristics.edit';

    protected function rules(): array
    {
        return [
            CrudAction::CREATE->value => (new PostRequest())->rules(),
            CrudAction::UPDATE->value => (new PutRequest())->rules(),
        ];
    }

    protected function afterReadAll(Model $model): array
    {
        return [
            'data' => $model->onlyParents()->paginate(config('common.pagination_limit')),
        ];
    }

    protected function pushCreateView()
    {
        $characteristics = Characteristic::all();

        return compact('characteristics');
    }

    protected function pushEditView(Model $model)
    {
        $characteristics = Characteristic::all();

        return compact('characteristics');
    }

    protected function beforeCreate(array &$validatedData, Request $request): ?array
    {
        $request->merge([
            'slug' => Str::slug($request->name),
        ]);

        $request->validate([
            'slug' => 'required|string|unique:characteristics,slug',
        ]);

        return $request->all();
    }

    protected function beforeUpdate(array &$validatedData, Model $model, Request $request): ?array
    {
        $request->merge([
            'slug' => Str::slug($request->name),
        ]);

        $request->validate([
            'slug' => 'required|string|unique:characteristics,slug,' . $model->id,
        ]);

        return $request->all();
    }

    protected function afterCreate(Model $model, Request $request): ?array
    {
        // Create children characteristics
        foreach ($request->children as $charName) {
            $this->findOrCreateCharacteristic($charName, $model->id);
        }

        return null;
    }

    protected function afterUpdate(Model $model, Request $request): ?array
    {
        $originalIds = $model->children()->pluck('id');
        $childrenIds = [];

        // Create children characteristics
        foreach ($request->children as $charName) {
            $childrenIds[] = $this->findOrCreateCharacteristic($charName, $model->id)->id;
        }

        // Delete old children characteristics
        Characteristic::whereIn('id', $originalIds->diff($childrenIds)->toArray())->delete();

        return null;
    }

    private function findOrCreateCharacteristic(string $name, $parentId)
    {
        return Characteristic::updateOrCreate([
            'slug' => Str::slug($name),
        ], [
            'name' => $name,
            'parent_characteristic_id' => $parentId,
        ]);
    }
}
