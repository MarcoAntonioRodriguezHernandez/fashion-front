<?php

namespace App\Http\Controllers\Base\Tags;

use App\Enums\CrudAction;
use App\Http\Controllers\Common\GenericCrudProvider;
use App\Http\Requests\Base\Tags\PostRequest;
use App\Http\Requests\Base\Tags\PutRequest;
use App\Models\Base\Tag;
use App\Traits\Helpers\HandlerFilesTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagsController extends GenericCrudProvider
{
    // Common config
    use HandlerFilesTrait;

    protected string $modelClass = Tag::class;

    protected string $indexView = 'base.tags.index';
    protected string $showView = 'base.tags.show';
    protected string $createView = 'base.tags.create';
    protected string $editView = 'base.tags.edit';

    protected function rules(): array
    {
        return [
            CrudAction::CREATE->value => (new PostRequest())->rules(),
            CrudAction::UPDATE->value => (new PutRequest())->rules(),
        ];
    }

    protected function beforeCreate(array &$validatedData, Request $request): ?array
    {
        return ['slug' => Str::slug($request->name)];
    }

    protected function beforeUpdate(array &$validatedData, Model $model, Request $request): ?array
    {
        if ($request->name)
            return ['slug' => Str::slug($request->name)];
    }
}
