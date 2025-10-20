<?php

namespace App\Http\Controllers\Base\EventType;

use App\Enums\CrudAction;
use App\Http\Controllers\Common\GenericCrudProvider;
use App\Http\Requests\Base\EventType\{
    PostRequest,
    PutRequest,
};
use App\Models\Base\EventType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Nette\NotImplementedException;

class EventTypeController extends GenericCrudProvider
{

    protected string $modelClass = EventType::class;

    protected string $indexView = 'base.event_types.index';
    protected string $createView = 'base.event_types.create';
    protected string $editView = 'base.event_types.edit';

    protected function rules(): array
    {
        return [
            CrudAction::CREATE->value => (new PostRequest())->rules(),
            CrudAction::UPDATE->value => (new PutRequest())->rules(),
        ];
    }
    protected function beforeCreate(array &$validatedData, Request $request): ?array
    {
        $request->merge([
            'slug' => Str::slug($request->name),
        ]);

        $request->validate([
            'slug' => 'required|string|unique:event_types,slug',
        ]);

        return $request->all();
    }

    protected function beforeUpdate(array &$validatedData, Model $model, Request $request): ?array
    {
        $request->merge([
            'slug' => Str::slug($request->name),
        ]);

        $request->validate([
            'slug' => 'required|string|unique:event_types,slug,' . $model->id,
        ]);

        return $request->all();
    }

    protected function readRecord($field)
    {
        throw new NotImplementedException(self::class . ' does not allow this operation');
    }
}
