<?php

namespace App\Http\Controllers\Base\Products;

use App\Enums\{
    CrudAction,
    CategoryStatuses,
    ProductSaleTypes,
};
use App\Http\Controllers\Common\GenericCrudProvider;
use App\Http\Requests\Base\Products\FullPostRequest;
use App\Http\Requests\Base\Provider\PostRequest as ProviderPostRequest;
use App\Http\Requests\Base\Invoice\PostRequest as InvoicePostRequest;
use App\Jobs\Marketplace\UploadProduct;
use App\Models\Base\{
    Category,
    Characteristic,
    Product,
    Tag,
    Color,
    Country,
    Designer,
    PricingScheme,
    Provider,
    Size,
    Invoice,
    InvoiceFile,
    PaymentType,
};
use App\Models\User;
use App\Traits\Base\Product\CreatesProduct;
use App\Traits\Helpers\{
    HandlerFilesTrait,
    Support,
};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\{
    Arr,
    Str,
};
use Nette\NotImplementedException;

class FullProductController extends GenericCrudProvider
{

    // Common config
    use HandlerFilesTrait, Support, CreatesProduct;

    protected string $modelClass = Product::class;

    protected string $indexView = 'base.supply.create.view'; // Only for redirection purposes
    protected string $createView = 'base.product.full-create';
    protected string $showView = '';
    protected string $editView = '';

    protected function rules(): array
    {
        return [
            CrudAction::CREATE->value => (new FullPostRequest())->rules(),
            CrudAction::UPDATE->value => [],
        ];
    }

    protected function pushCreateView()
    {
        $categories = (Category::getByStatus(CategoryStatuses::ACTIVE));
        $designers = Designer::all();
        $providers = Provider::all();
        $tags = Tag::all();
        $colors = Color::active()->onlyChildren()->get();
        $sizes = Size::all();
        $countries = Country::all();
        $pricesScheme = PricingScheme::all();
        $characteristics = Characteristic::onlyParents()->get();
        $invoices = Invoice::all();
        $invoicesFile = InvoiceFile::all();
        $users = User::employees()->get();
        $paymentMethods = PaymentType::all();

        return compact('categories', 'designers', 'tags', 'colors', 'sizes', 'providers', 'characteristics', 'countries', 'pricesScheme', 'invoices', 'invoicesFile', 'users', 'paymentMethods');
    }

    protected function beforeCreate(array &$validatedData, Request $request): ?array
    {   
        $providerRules = $request->has('provider') ? $this->getProviderValidationRules() : [];
        $invoiceRules = $request->has('invoice') ? $this->getInvoiceValidationRules() : [];

        $productSlug = Str::slug($request->name);
        $randomInternalCode = Str::random(12);
        $randomSku = Str::random(12);

        $pricingSchemeId = $request->sale_type == ProductSaleTypes::SALE->value ? PricingScheme::orderBy('id')->value('id') : $request->pricing_scheme_id;

        $request->merge([
            'slug' => $productSlug,
            'internal_code' => $randomInternalCode,
            'sku' => $randomSku,
            'pricing_scheme_id' => $pricingSchemeId,
            'desired' => false,
            'status' => true,
            'tags' => $request->get('tags', []),
        ]);

        $request->validate([
            'slug' => 'required|string|unique:products,slug',
            'internal_code' => 'required|string|unique:products,internal_code',
            'sku' => 'required|string|unique:products,sku',
            'pricing_scheme_id' => 'required|integer|exists:pricing_schemes,id',
            ...$providerRules,
            ...$invoiceRules,
        ]);

        $validatedData['slug'] = $productSlug;
        $validatedData['internal_code'] = $randomInternalCode;
        $validatedData['sku'] = $randomSku;
        $validatedData['pricing_scheme_id'] = $pricingSchemeId;
        $validatedData['desired'] = false;
        $validatedData['status'] = true;

        return $validatedData;
    }

    protected function afterCreate(Model $product, Request $request): ?array
    {
        $provider = $this->commitTransaction(fn() => $this->findOrCreateProvider($request->provider_id, $request->provider));
        $invoice = [
            'id' => $request->invoice_id,
            'data' => $request->invoice,
        ];

        $this->postCreateProduct($product, $request->images, $request->tags, [$provider->id], $request->characteristics);

        $this->postFullCreateProduct($product, $invoice, $request->inventory);

        UploadProduct::dispatch($product->slug);

        return [$product->id];
    }

    protected function readRecord($field)
    {
        throw new NotImplementedException('Use ' . ProductController::class . ' for this operation');
    }

    protected function readAllRecords(Request $request)
    {
        throw new NotImplementedException('Use ' . ProductController::class . ' for this operation');
    }

    protected function updateRecord(Request $request)
    {
        throw new NotImplementedException('Use ' . ProductController::class . ' for this operation');
    }

    protected function deleteRecord($field)
    {
        throw new NotImplementedException('Use ' . ProductController::class . ' for this operation');
    }

    private function findOrCreateProvider($id = null, array $providerData = null)
    {
        // If no data provided, initialize values
        $providerData ??= [
            'name' => '',
            'designers' => [],
        ];

        $provider =  Provider::findOr($id, fn() => Provider::create([
            ...$providerData,
            'slug' => Str::slug($providerData['name']),
        ]));

        $designerIds = $this->getModelIds(collect($providerData['designers']), Designer::class);

        $provider->designers()->sync($designerIds);

        return $provider;
    }

    private function getProviderValidationRules()
    {
        return Arr::prependKeysWith((new ProviderPostRequest())->rules(), 'provider.');
    }

    private function getInvoiceValidationRules()
    {
        return Arr::prependKeysWith((new InvoicePostRequest())->rules(), 'invoice.');
    }
}
