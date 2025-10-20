<?php

namespace App\Http\Controllers\Base\Products;

use App\Enums\{
    CrudAction,
    CategoryStatuses,
};
use App\Http\Controllers\Common\GenericCrudProvider;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Base\Products\{
    PostRequest,
    PutRequest,
};
use App\Jobs\Marketplace\{
    RefreshProduct,
    RefreshProductMedia,
    RefreshProductVariant,
    UploadProduct,
};
use App\Models\Base\{
    Category,
    Characteristic,
    Product,
    Tag,
    Color,
    Designer,
    Provider,
    Size,
    PricingScheme,
    ProductVariant,
};
use App\Services\Marketplace\RefreshProductService;
use App\Traits\Base\Product\{
    CreatesProduct,
    MassUpdate,
};
use App\Traits\Helpers\HandlerFilesTrait;

use Exception;
use Illuminate\Database\Eloquent\{
    Builder,
    Model,
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends GenericCrudProvider
{

    // Common config
    use HandlerFilesTrait, MassUpdate, CreatesProduct;

    protected string $modelClass = Product::class;

    protected string $indexView = 'base.product.index';
    protected string $showView = 'base.product.show';
    protected string $createView = 'base.product.create';
    protected string $editView = 'base.product.edit';

    protected ?string $searchField = 'name';

    private RefreshProductService $refreshProductService;

    public function __construct()
    {
        parent::__construct();

        $this->refreshProductService = new RefreshProductService();
    }

    protected function rules(): array
    {
        return [
            CrudAction::CREATE->value => (new PostRequest())->rules(),
            CrudAction::UPDATE->value => (new PutRequest())->rules(),
        ];
    }

    protected function beforeReadAll(Builder $query, Request $request): Builder
    {
        return $query->orderBy('name');
    }

    protected function createView()
    {
        throw new Exception('Method not implemented');
    }

    protected function pushEditView(Model $model)
    {
        $categories = (Category::getByStatus(CategoryStatuses::ACTIVE));
        $designers = Designer::all();
        $providers = Provider::all();
        $pricingSchemes = PricingScheme::all();
        $tags = Tag::all();
        $colors = Color::active()->onlyChildren()->get();
        $sizes = Size::all();
        $characteristics = Characteristic::onlyParents()->get();

        return compact('categories', 'designers', 'tags', 'colors', 'sizes', 'providers', 'characteristics', 'pricingSchemes');
    }


    protected function beforeCreate(array &$validatedData, Request $request): ?array
    {
        $request->merge([
            'slug' => Str::slug($request->name),
            'desired' => false,
        ]);

        $request->validate([
            'slug' => 'required|string|unique:products,slug',
        ]);

        return $request->all();
    }

    protected function beforeUpdate(array &$validatedData, Model $model, Request $request): ?array
    {
        $request->merge([
            'slug' => Str::slug($request->name),
            'title' => $this->cleanTitle($request->title),
        ]);

        $request->validate([
            'slug' => 'required|string|unique:products,slug,' . $model->id,
            'origin_code' => 'required|string|unique:products,origin_code,' . $model->id,
            'internal_code' => 'required|string|unique:products,internal_code,' . $model->id,
        ]);

        return $request->all();
    }

    protected function afterUpdate(Model $model, Request $request): ?array
    {
        $deletedImages = $model->images()->whereNotIn('id', $request->keep_images ?? [])->get();

        $this->postCreateProduct($model, $request->images ?? [], $request->tags ?? [], $request->providers ?? [], $request->characteristics ?? []);

        $validImages = $model->images()
            ->whereNotIn('id', $deletedImages->pluck('id'))
            ->get()
            ->mapToGroups(fn($image) => [$image->color_id => $image]);

        $deletedImages->each(function ($image) use ($validImages) {
            $image->productVariants()
                ->update([
                    'product_image_id' => $validImages->get($image->color_id, $validImages->first())?->first()->id,
                ]);
        });

        $mediaColorList = $deletedImages->pluck('color_id')
            ->merge(array_column($request->images ?? [], 'color_id'))
            ->unique();

        $deletedImages->each(fn($i) => $i->delete());

        if ($mediaColorList->isEmpty()) {
            Log::info("[afterUpdate] Dispatch directo de RefreshProduct");
            RefreshProduct::dispatch($model->slug)->onQueue('marketplace-refresh');
        } else {
            $this->refreshProductService->chainRefreshFromColors($model->slug, $mediaColorList, [
                RefreshProductMedia::class,
            ])
                ->prependJob(new RefreshProduct($model->slug))
                ->when($model->wasChanged(['full_price', 'pricing_scheme_id']), fn($chain) => $chain->appendJob(new RefreshProductVariant($model->slug)))
                ->dispatch();
        }

        return null;
    }

    protected function afterCreate(Model $model, Request $request): ?array
    {
        $this->postCreateProduct($model, $request->images, $request->tags ?? [], $request->providers ?? [], $request->characteristics ?? []);
        UploadProduct::dispatch($model->slug);

        return null;
    }

    public function variantsView($field)
    {
        $product = $this->getModel($field);
        $productImages = $product->images()->with('color:id,name,hexadecimal')->orderBy('color_id')->get();
        $productVariants = $product->productVariants()
            ->with(['productImage', 'variant.size:id,name,number', 'variant.color:id,name,hexadecimal'])
            ->get()
            ->mapToGroups(fn($pv) => [$pv->variant->color_id => $pv])
            ->map(function ($group) {
                return [
                    'color' => $group->first()->variant->color,
                    'sizes' => $group->map(fn($pv) => $pv->variant->size)->unique('id'),
                    'images' => $group->map(fn($pv) => $pv->productImage)->unique('id'),
                    'total_items' => $group->sum(fn($pv) => $pv->items()->count()),
                ];
            })
            ->values();

        return Inertia::render('Product/Variants', compact('product', 'productImages', 'productVariants'));
    }

    public function variantsUpdate($field, Request $request)
    {
        $product = $this->getModel($field);
        $product->productVariants()
            ->whereHas('variant', fn($q) => $q->where('color_id', $request->color_id))
            ->update([
                'product_image_id' => $request->product_image_id,
            ]);

        return redirect()->back()->with('success', 'Variants updated successfully');
    }

    public function variantsDelete($field, $colorId)
    {
        $product = $this->getModel($field);

        $productVariants = $product->productVariants()
            ->whereHas('variant', fn($q) => $q->where('color_id', $colorId));

        if ($productVariants->clone()->get()->sum(fn($pv) => $pv->items()->count()) > 0)
            return redirect()->back()->with('error', 'You can not delete variants with items');

        $marketplaceProductVariants = DB::table('marketplace_objects')
            ->where('conspiracy_type', ProductVariant::class)
            ->whereIn('conspiracy_id', $productVariants->pluck('id'));

        $marketplaceInventoryItems = DB::table('marketplace_objects')
            ->where('conspiracy_type', 'App\Models\Support\InventoryItem')
            ->whereIn('parent_object_id', $marketplaceProductVariants->pluck('id'));

        DB::table('marketplace_codes')
            ->where(fn($q) => $q->where('codable_type', 'App\Models\Shopify\ShopifyProductVariant')
                ->whereIn('codable_id', $marketplaceProductVariants->pluck('id')))
            ->orWhere(fn($q) => $q->where('codable_type', 'App\Models\Shopify\ShopifyInventoryItem')
                ->whereIn('codable_id', $marketplaceInventoryItems->pluck('id')))
            ->delete();

        $marketplaceProductVariants->delete();
        $marketplaceInventoryItems->delete();

        $productVariants->delete();

        return redirect()->back()->with('success', 'Variants deleted successfully');
    }

    protected function whileDelete(Model $model)
    {
        $this->refreshProductService->deleteProductMarketplace($model->slug);
    }

    protected function deleteRecord($field)
    {
        try {
            $model = $this->getModel($field);

            $this->beforeDelete($model);

            $this->commitTransaction(function () use ($model) {
                $model->update([
                    'status' => false,
                ]);

                $this->whileDelete($model);
            });
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
            return $this->makeResponse([
                'message' => 'Product not found',
                'success' => false,
                'status' => Response::HTTP_NOT_FOUND,
            ]);
        } catch (Exception $exception) {
            return $this->makeResponse([
                'message' => 'Failed to deactivate product',
                'success' => false,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'exception' => $exception,
            ]);
        }
    }
    public function readAllRecords(Request $request)
    {
        try {
            $model = $this->getModelInstance();

            $perPage = in_array((int) $request->get('per_page'), [10, 25, 50])
                ? (int) $request->get('per_page')
                : 10;

            $currentFilters = $request->except(['page', 'per_page']);
            $previousFilters = session('previous_filters', []);
            if ($currentFilters != $previousFilters) {
                $request->merge(['page' => 1]);
            }
            session(['previous_filters' => $currentFilters]);

            $query = $model->query();

            if ($request->has($this->searchField) && !empty($request->get($this->searchField))) {
                $searchTerm = $request->get($this->searchField);
                $query->where($this->searchField, 'like', '%' . $searchTerm . '%');
            }

            if (!$request->has('sort_by')) {
                $query->orderBy('name');
            }

            $this->beforeReadAll($query, $request);

            $data = $query->paginate($perPage)
                ->appends($request->query());

            $postReadAllResponse = $this->afterReadAll($model);

            if ($this->searchField) {
                $postReadAllResponse['searchBy'] = $this->searchField;
            }

            return $this->makeResponse([
                'view' => $this->getIndexViewName(),
                'data' => [
                    "data" => $data,
                    'per_page' => $perPage,
                    ...$postReadAllResponse
                ],
                'message' => 'Records retrieved successfully',
                'status' => \Symfony\Component\HttpFoundation\Response::HTTP_OK,
            ]);
        } catch (\Exception $exception) {
            return $this->makeResponse([
                'message' => 'Error retrieving records',
                'success' => false,
                'status' => \Symfony\Component\HttpFoundation\Response::HTTP_INTERNAL_SERVER_ERROR,
                'exception' => $exception,
            ]);
        }
    }
}
