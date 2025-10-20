<?php

namespace App\Http\Controllers\Base\Country;

use App\Enums\CrudAction;
use App\Http\Controllers\Common\GenericCrudProvider;
use App\Models\Base\Country;
use App\Traits\Helpers\HandlerFilesTrait;
use Illuminate\Http\Request;
use Nette\NotImplementedException;

class CountryController extends GenericCrudProvider
{
    // Common config
    use HandlerFilesTrait;

    protected string $modelClass = Country::class;

    protected string $indexView = 'base.country.index';


    protected function rules(): array
    {
        return [
            CrudAction::CREATE->value => [],
            CrudAction::UPDATE->value => [],
        ];
    }

    protected function readRecord($field)
    {
        throw new NotImplementedException('This operation is not implemented');
    }

    protected function createRecord(Request $request)
    {
        throw new NotImplementedException('This operation is not implemented');
    }

    protected function updateRecord(Request $request)
    {
        throw new NotImplementedException('This operation is not implemented');
    }

    protected function deleteRecord($field)
    {
        throw new NotImplementedException('This operation is not implemented');
    }
}
