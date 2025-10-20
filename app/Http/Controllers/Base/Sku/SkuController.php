<?php

namespace App\Http\Controllers\Base\Sku;

use App\Enums\CrudAction;
use App\Http\Controllers\Common\GenericCrudProvider;
use App\Http\Requests\Base\Sku\PostRequest;
use App\Http\Requests\Base\Sku\PutRequest;
use App\Models\Base\Sku;
use App\Traits\Helpers\HandlerFilesTrait;
class SkuController extends GenericCrudProvider
{
    use HandlerFilesTrait;

    protected string $modelClass = Sku::class;

    protected string $indexView = 'base.sku.index';
    protected string $showView = 'base.sku.show';
    protected string $createView = 'base.sku.create';
    protected string $editView = 'base.sku.edit';

    protected function rules(): array
    {
        return [
            CrudAction::CREATE->value => (new PostRequest())->rules(),
            CrudAction::UPDATE->value => (new PutRequest())->rules(),
        ];
    }
}
