<?php

namespace App\Http\Controllers\Base\ProductImage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Base\ProductImage\{
    PostRequest,
    PutRequest,
};
use Illuminate\Database\Eloquent\Model;
use App\Traits\Helpers\HandlerFilesTrait;
use App\Enums\CrudAction;
use App\Http\Controllers\Common\GenericCrudProvider;
use App\Models\Base\Color;
use App\Models\Base\Product;
use App\Models\Base\ProductImage;

class ProductImageController extends GenericCrudProvider
{
    // Common config
    use HandlerFilesTrait;

    protected string $modelClass = ProductImage::class;

    protected string $indexView = 'base.product_image.index';
    protected string $showView = 'base.product_image.show';
    protected string $createView = 'base.product_image.create';
    protected string $editView = 'base.product_image.edit';

    protected function rules(): array
    {
        return [
            CrudAction::CREATE->value => (new PostRequest())->rules(),
            CrudAction::UPDATE->value => (new PutRequest())->rules(),
        ];
    }

    // CREATE ACTION
    protected function beforeCreate(array &$validatedData, Request $request): ?array
    {
        // Image processing and storage
        if ($request->hasFile('src_image')) {
            $folder = 'images';
            $imagePath = $this->getUrl($this->upload($request->file('src_image'), $folder));
            $data = array_merge( ['src_image' => $imagePath]);
        }

        return $data;
    }

    protected function pushCreateView()
    {
        $colors = Color::all();

        $products = Product::all();

        return compact('colors','products');
    }

    // UPDATE ACTION
    protected function beforeUpdate(array &$validatedData, Model $model, Request $request): ?array
    {

        if ($request->hasFile('src_image')) {
            // Delete the previous image if it exists
            $this->deleteFileIfExists($model->src_image);

            // Upload the new image and update the image path in $data
            $folder = 'images';
            $newImagePath = $this->getUrl($this->upload($request->file('src_image'), $folder));
            $data = array_merge(['src_image' => $newImagePath]);
        }

        return $data;
    }

    protected function pushEditView(Model $model)
    {
        $colors = Color::all();

        $products = Product::all();

        return compact('colors','products');
    }
}
