<?php

namespace App\Http\Controllers\Base\PaymentType;

use App\Enums\CrudAction;
use App\Http\Controllers\Common\GenericCrudProvider;
use App\Http\Requests\Base\PaymentType\PostRequest;
use App\Http\Requests\Base\PaymentType\PutRequest;
use App\Enums\PaymentTypeVisibilities;
use App\Models\Base\PaymentType;
use App\Traits\Helpers\HandlerFilesTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class PaymentTypeController extends GenericCrudProvider
{
    use HandlerFilesTrait;

    protected string $modelClass = PaymentType::class;

    protected string $indexView = 'base.payment_type.index';
    protected string $showView = 'base.payment_type.show';
    protected string $createView = 'base.payment_type.create';
    protected string $editView = 'base.payment_type.edit';

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
    public function setVisibility(Request $request, PaymentType $paymentType)
    {
        $visible = PaymentTypeVisibilities::VISIBLE->value;
        $hidden  = PaymentTypeVisibilities::HIDDEN->value;

        $value = (int) $request->input('visibility', $hidden);
        if (!in_array($value, [$visible, $hidden], true)) {
            $value = $hidden;
        }

        $paymentType->visibility = $value;
        $paymentType->save();

        return back()->with('success', 'Estado actualizado');
    }

    protected function beforeUpdate(array &$validatedData, Model $model, Request $request): ?array
    {
        if ($request->name)
            return ['slug' => Str::slug($request->name)];
    }
}
