<?php

namespace App\Http\Controllers\Base\Notification;

use App\Enums\{
    CrudAction,
    NotificationTypes,
};
use App\Http\Controllers\Common\GenericCrudProvider;
use App\Http\Requests\Base\Cancellation\{
    SearchValidUsersRequest,
};
use App\Http\Requests\Base\Notification\{
    PostRequest,
    PutRequest
};
use App\Models\Base\Notification;
use App\Models\User;
use App\Traits\Base\Notification\HandlesUsers;
use Illuminate\Http\{
    Request,
    Response,
};
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Nette\NotImplementedException;

class NotificationController extends GenericCrudProvider
{
    use HandlesUsers;

    protected string $modelClass = Notification::class;

    protected string $indexView = 'base.notification.index';
    protected string $showView = 'base.notification.show';

    protected function rules(): array
    {
        return [
            CrudAction::CREATE->value => (new PostRequest())->rules(),
            CrudAction::UPDATE->value => (new PutRequest())->rules(),
        ];
    }

    public function updateUserNotifications(Request $request, $user)
    {
        return $this->processRequest(function () use ($request, $user) {
            User::findOrFail($user)->employeeDetail()->update([
                'notifications_allowed' => join('-', $request->notifications) ?: null,
            ]);
        }, 'Could not find the user', 'Error updating user notifications');
    }

    protected function searchNonNotifiedUsers(SearchValidUsersRequest $request)
    {
        return $this->processRequest(function () use ($request) {
            $users = $this->findNonNotifiedUsers($request->search);

            Session::flash('usersResult', $users);

            return $this->makeResponse([
                'status' => Response::HTTP_OK,
                'redirect' => null,
            ]);
        }, '', 'Error while searching user');
    }

    public function index(Request $request)
    {
        $notifications = Notification::with('orderMarketplace')->latest()->get();

        return Inertia::render('base.notification.index', [
            'notifications' => $notifications,
        ]);
    }

    protected function editNotifiedUsersView()
    {
        $users = $this->findNotifiedUsers()->keyBy('id');
        $notificationTypes = NotificationTypes::getAllNames();

        return Inertia::render('Notification/EditNotifyUsers', compact('users', 'notificationTypes'));
    }

    protected function updateRecord(Request $request)
    {
        throw new NotImplementedException('Can not perform this operation');
    }

    protected function deleteRecord($field)
    {
        throw new NotImplementedException('Can not perform this operation');
    }
}
