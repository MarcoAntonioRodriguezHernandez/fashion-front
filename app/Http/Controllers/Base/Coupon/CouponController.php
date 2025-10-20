<?php

namespace App\Http\Controllers\Base\Coupon;

use App\Enums\{
    CrudAction,
    CategoryStatuses
};
use App\Http\Controllers\Common\GenericCrudProvider;
use App\Http\Requests\Base\Coupon\{
    PostRequest,
    PutRequest,
};
use App\Models\Base\{
    Category,
    Coupon,
};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class CouponController extends GenericCrudProvider
{
    // Common config
    protected string $modelClass = Coupon::class;

    protected string $indexView = 'base.coupon.index';
    protected string $showView = 'base.coupon.show';
    protected string $createView = 'base.coupon.create';
    protected string $editView = 'base.coupon.edit';

    protected function rules(): array
    {
        return [
            CrudAction::CREATE->value => (new PostRequest())->rules(),
            CrudAction::UPDATE->value => (new PutRequest())->rules(),
        ];
    }

    protected function beforeCreate(array &$validatedData, Request $request): ?array
    {
        $request->validate([
            'code' => 'required|string|unique:coupons,code',
        ]);

        return $request->all();
    }

    protected function beforeUpdate(array &$validatedData, Model $model, Request $request): ?array
    {
        $request->validate([
            'code' => 'required|string|unique:coupons,code,' . $model->id,
        ]);

        return $request->all();
    }

    protected function pushCreateView()
    {
        $categories = (Category::getByStatus(CategoryStatuses::ACTIVE));
        return compact('categories');
    }

    protected function pushEditView(Model $model)
    {
        $categories = (Category::getByStatus(CategoryStatuses::ACTIVE));
        return compact('categories');
    }

}
