<?php

namespace App\Http\Controllers\Base\Event;

use App\Enums\CrudAction;
use App\Http\Controllers\Common\GenericCrudProvider;
use App\Http\Requests\Base\Event\PostRequest;
use App\Models\Base\Event;
use Illuminate\Http\Request;
use Nette\NotImplementedException;

class EventController extends GenericCrudProvider
{

    protected string $modelClass = Event::class;

    protected string $indexView = 'base.event.index';

    protected function rules(): array
    {
        return [
            CrudAction::CREATE->value => (new PostRequest())->rules(),
            CrudAction::UPDATE->value => null,
        ];
    }

    protected function readRecord($field)
    {
        throw new NotImplementedException(EventController::class . ' does not allow this operation');
    }

    protected function createRecord(Request $request)
    {
        throw new NotImplementedException(EventController::class . ' does not allow this operation');
    }

    protected function updateRecord(Request $request)
    {
        throw new NotImplementedException(EventController::class . ' does not allow this operation');
    }
}
