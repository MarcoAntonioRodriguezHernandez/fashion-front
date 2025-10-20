<?php

namespace App\Http\Controllers\Base\Colors;

use App\Enums\CrudAction;
use App\Http\Controllers\Common\GenericCrudProvider;
use App\Http\Requests\Base\Colors\{
    PostRequest,
    PutRequest,
};
use App\Models\Base\Color;
use App\Traits\Helpers\HandlerFilesTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ColorController extends GenericCrudProvider
{
    use HandlerFilesTrait;

    // Common config
    protected string $modelClass = Color::class;

    protected string $indexView = 'base.color.index';
    protected string $showView = 'base.color.show';
    protected string $createView = 'base.color.create';
    protected string $editView = 'base.color.edit';

    protected function rules(): array
    {
        return [
            CrudAction::CREATE->value => (new PostRequest())->rules(),
            CrudAction::UPDATE->value => (new PutRequest())->rules(),
        ];
    }

    protected function readAllRecords(Request $request)
    {
        return $this->processRequest(function () use ($request) {
            $data = Color::onlyParents()->with('children')->orderBy('status', 'desc')->get();

            return Inertia::render('Color/Index', compact('data'));
        }, 'Error fetching colors', 'Error fetching colors');
    }

    protected function createView()
    {
        return $this->processRequest(function () {
            $colorShades = Color::onlyParents()->get();

            return Inertia::render('Color/Create', compact('colorShades'));
        }, 'Error fetching colors', 'Error fetching colors');
    }

    protected function beforeCreate(array &$validatedData, Request $request): ?array
    {
        return [
            'slug' => Str::slug($request->name),
            'parent_color_id' => $request->parent_color_id ?: null,
        ];
    }

    protected function afterCreate(Model $model, Request $request): ?array
    {
        if ($request->hasFile('texture_src')) {
            $model->update([
                'texture_src' => $this->upload($request->file('texture_src'), 'colors', 'public', $model->slug),
            ]);
        }

        return null;
    }

    protected function readRecord($field)
    {
        return $this->processRequest(function () use ($field) {
            $color = Color::findOrFail($field);
            $color->children = Color::where('parent_color_id', $field)->get();

            return Inertia::render('Color/Show', compact('color'));
        }, 'Could not find color', 'Error fetching color');
    }

    protected function editView($field)
    {
        return $this->processRequest(function () use ($field) {
            $color = Color::with('children')->findOrFail($field);

            return Inertia::render('Color/Edit', compact('color'));
        }, 'Could not find color', 'Error fetching color');
    }

    protected function beforeUpdate(array &$validatedData, Model $model, Request $request): ?array
    {
        return [
            'slug' => Str::slug($request->name),
        ];
    }

    protected function afterUpdate(Model $model, Request $request): ?array
    {
        if ($request->hasFile('texture_src')) {
            $model->update([
                'texture_src' => $this->upload($request->file('texture_src'), 'colors', 'public', $model->slug),
            ]);
        }

        return null;
    }

    protected function deleteRecord($field)
    {
        return $this->processRequest(function () use ($field) {
            $color = Color::findOrFail($field);

            $color->children()->update(['status' => false]);

            $color->update(['status' => false]);

            return redirect()->back()->with('success', 'Color deleted successfully');
        }, 'Could not find color', 'Error deleting color');
    }

    protected function reactiveRecord($field)
    {
        return $this->processRequest(function () use ($field) {
            $color = Color::with('children')->findOrFail($field);

            foreach ($color->children as $child)
                $child->update(['status' => true]);

            $color->update(['status' => true]);

            return redirect()->back()->with('success', 'Color reactivated successfully');
        }, 'Could not find color', 'Error deleting color');
    }

    protected function getIndexViewName()
    {
        if (request()->inertia() && request()->route()->getName() == 'base.color.edit')
            return null; // Force redirect back

        return parent::getIndexViewName();
    }
}
