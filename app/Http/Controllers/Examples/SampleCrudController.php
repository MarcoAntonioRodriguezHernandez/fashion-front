<?php

namespace App\Http\Controllers\Examples;

use App\Enums\CrudAction;
use App\Http\Controllers\Common\GenericCrudProvider;
use App\Http\Requests\Examples\{
    PostRequest,
    PutRequest,
};
use App\Models\Examples\{
    Example,
    ExampleType,
};
use App\Traits\Helpers\HandlerFilesTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SampleCrudController extends GenericCrudProvider
{

    // Common config
    use HandlerFilesTrait;

    protected string $modelClass = Example::class;

    protected string $indexView = 'example.index';
    protected string $showView = 'example.show';
    protected string $createView = 'example.create';
    protected string $editView = 'example.edit';

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
        $data = ['slug' => Str::slug($request->name)];

        // Image processing and storage
        if ($request->hasFile('image')) {
            $folder = 'images';
            $imagePath = $this->getUrl($this->upload($request->file('image'), $folder));
            $data = array_merge($data, ['image' => $imagePath]);
        }

        return $data;
    }

    protected function pushCreateView()
    {
        $types = ExampleType::all();

        return compact('types');
    }

    // UPDATE ACTION
    protected function beforeUpdate(array &$validatedData, Model $model, Request $request): ?array
    {
        $data = ['slug' => Str::slug($request->name)];

        if ($request->hasFile('image')) {
            // Delete the previous image if it exists
            $this->deleteFileIfExists($model->image);

            // Upload the new image and update the image path in $data
            $folder = 'images';
            $newImagePath = $this->getUrl($this->upload($request->file('image'), $folder));
            $data = array_merge($data, ['image' => $newImagePath]);
        }

        return $data;
    }

    protected function pushEditView(Model $model)
    {
        $types = ExampleType::all();

        return compact('types');
    }

    // DELETE ACTION
    protected function beforeDelete(Model $model)
    {
        $this->deleteFileIfExists($model->image);
    }
}
