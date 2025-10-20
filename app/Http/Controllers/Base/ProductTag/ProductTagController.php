<?php

namespace App\Http\Controllers\Base\ProductTag;

use App\Enums\CrudAction;
use App\Http\Controllers\Common\GenericCrudProvider;
use App\Http\Requests\Base\ProductTag\{
    PostRequest,
    PutRequest,
};
use App\Models\Base\{
    Product,
    Tag,
    ProductTag,
};
use Illuminate\Database\Eloquent\Model;

class ProductTagController extends GenericCrudProvider{
    
    protected string $modelClass = ProductTag::class;

    protected string $indexView = 'base.product_tag.index';
    protected string $showView = 'base.product_tag.show';
    protected string $createView = 'base.product_tag.create';
    protected string $editView = 'base.product_tag.edit';

    protected function rules(): array
    {
        return [
            CrudAction::CREATE->value => (new PostRequest())->rules(),
            CrudAction::UPDATE->value => (new PutRequest())->rules(),
        ];
    }

    protected function pushCreateView()
    {
        $products = Product::all();
        $tags = Tag::all();

        return compact('products', 'tags');
    }

    protected function pushEditView(Model $model)
    {
        $products = Product::all();
        $tags = Tag::all();

        return compact('products', 'tags');
    }

}
