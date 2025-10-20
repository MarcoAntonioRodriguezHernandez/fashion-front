<?php

namespace App\Http\Controllers\Base\Variant;

use App\Enums\CrudAction;
use App\Http\Controllers\Common\GenericCrudProvider;
use App\Http\Requests\Base\Variant\PostRequest;
use App\Http\Requests\Base\Variant\PutRequest;
use App\Models\Base\{
    Variant,
    Size,
    Color
};
use App\Traits\Helpers\HandlerFilesTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VariantController extends GenericCrudProvider
{
    use HandlerFilesTrait;

    protected string $modelClass = Variant::class;

    protected string $indexView = 'base.variant.index';
    protected string $showView = 'base.variant.show';
    protected string $createView = 'base.variant.create';
    protected string $editView = 'base.variant.edit';

    protected function rules(): array
    {
        return [
            CrudAction::CREATE->value => (new PostRequest())->rules(),
            CrudAction::UPDATE->value => (new PutRequest())->rules(),
        ];
    }

    protected function pushCreateView()
    {
        $sizes = Size::all();
        $colors = Color::active()->onlyChildren()->get();
        return compact('sizes', 'colors');
    }

    protected function pushEditView(Model $model)
    {
        $sizes = Size::all();
        $colors = Color::active()->onlyChildren()->get();
        return compact('sizes', 'colors');
    }
}
