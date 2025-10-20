<?php

namespace App\Http\Controllers\Base\UserAddress;

use App\Enums\CrudAction;
use App\Http\Controllers\Common\GenericCrudProvider;
use App\Http\Requests\Base\UserAddress\{
    PostRequest,
    PutRequest,
};
use App\Models\Base\UserAddress;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Nette\NotImplementedException;

class UserAddressController extends GenericCrudProvider
{

    protected string $modelClass = UserAddress::class;

    protected string $indexView = 'base.user.addresses';
    protected string $showView = 'base.user_address.show';
    protected string $createView = 'base.user_address.create';
    protected string $editView = 'base.user_address.edit';

    protected function rules(): array
    {
        return [
            CrudAction::CREATE->value => (new PostRequest())->rules(),
            CrudAction::UPDATE->value => (new PutRequest())->rules(),
        ];
    }

    protected function pushCreateView()
    {
        $user = request('userId') ? User::findOrFail(request('userId')) : null;

        return compact('user');
    }
    protected function afterCreate(Model $model, Request $request): ?array
    {
        return [
            'id' => $model->user_id,
        ];
    }

    protected function afterUpdate(Model $model, Request $request): ?array
    {
        return [
            'id' => $model->user_id,
        ];
    }

    protected function readAllRecords(Request $request)
    {
        throw new NotImplementedException(UserAddressController::class . ' does not allow this operation');
    }

    protected function getIndexViewName()
    {
        if (request()->inertia() && request()->route()->getName() == 'base.user_addresses.create') {
            return null; // Force redirect back
        }

        return parent::getIndexViewName();
    }
}
