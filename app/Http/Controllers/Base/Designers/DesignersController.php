<?php

namespace App\Http\Controllers\Base\Designers;

use App\Enums\CrudAction;
use App\Http\Controllers\Common\GenericCrudProvider;
use App\Http\Requests\Base\Designers\PostRequest;
use App\Http\Requests\Base\Designers\PutRequest;
use App\Models\Base\Designer;
use App\Traits\Helpers\HandlerFilesTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DesignersController extends GenericCrudProvider
{
    // Common config
    use HandlerFilesTrait;

    protected string $modelClass = Designer::class;

    protected string $indexView = 'base.designers.index';
    protected string $showView = 'base.designers.show';
    protected string $createView = 'base.designers.create';
    protected string $editView = 'base.designers.edit';

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
