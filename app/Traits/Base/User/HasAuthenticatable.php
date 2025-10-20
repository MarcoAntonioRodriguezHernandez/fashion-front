<?php

namespace App\Traits\Base\User;

use App\Models\User;
use App\Http\Requests\Base\User\{
    UpdateEmailRequest,
    UpdatePasswordRequest,
    UpdateRolesRequest,
};
use App\Models\Auth\Role;
use Illuminate\Support\Facades\{
    Auth,
    Hash,
};
use Illuminate\Validation\ValidationException;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

trait HasAuthenticatable
{

    public function editRolesView($id)
    {
        return $this->processRequest(function () use ($id) {
            $user = User::findOrFail($id);
            $roles = Role::all();
            $currentRoles = $user->roles()->get()->pluck('id');

            return $this->makeResponse([
                'view' => 'base.user.edit-roles',
                'data' => ['data' => (object) compact('user', 'roles', 'currentRoles')],
                'message' => 'Roles edition view',
                'status' => Response::HTTP_OK,
            ]);
        }, 'User not found', 'Could not get the roles edition view');
    }

    public function updateRoles(UpdateRolesRequest $request, $id)
    {
        return $this->processRequest(function () use ($request, $id) {
            $user = User::findOrFail($id);

            $user->roles()->sync($request->roles ?? []);

            return $this->makeResponse([
                'data' => ['data' => $user->roles],
                'message' => 'Roles updated successfully',
                'status' => Response::HTTP_OK,
                'redirect' => 'base.user.show',
                'redirectParams' => ['id' => $user->id],
            ]);
        }, 'User not found', 'Could not update the roles');
    }

    public function editEmailView($id)
    {
        return $this->processRequest(function () use ($id) {
            $user = User::findOrFail($id);

            return $this->makeResponse([
                'view' => 'base.user.edit-email',
                'data' => ['data' => $user],
                'message' => 'Email edition view',
                'status' => Response::HTTP_OK,
            ]);
        }, 'User not found', 'Could not get the email edition view');
    }

    public function updateEmail(UpdateEmailRequest $request, $id)
    {
        return $this->processRequest(function () use ($request, $id) {
            $user = User::findOrFail($id);

            $this->validatePassword($request->password, $user, 'password');

            $user->update([
                'email' => $request->email,
            ]);

            return $this->makeResponse([
                'data' => ['data' => $user->email],
                'message' => 'Email updated successfully',
                'status' => Response::HTTP_OK,
                'redirect' => 'base.user.show',
                'redirectParams' => ['id' => $user->id],
            ]);
        }, 'User not found', 'Could not update the email');
    }

    public function editPasswordView($id)
    {
        return $this->processRequest(function () use ($id) {
            $user = User::findOrFail($id);

            return $this->makeResponse([
                'view' => 'base.user.edit-password',
                'data' => ['data' => $user],
                'message' => 'Password edition view',
                'status' => Response::HTTP_OK,
            ]);
        }, 'User not found', 'Could not get the password edition view');
    }

    public function updatePassword(UpdatePasswordRequest $request, $id)
    {
        return $this->processRequest(function () use ($request, $id) {
            $user = User::findOrFail($id);

            $this->validatePassword($request->current_password, $user, 'current_password');

            $user->update([
                'password' => Hash::make($request->new_password),
            ]);

            return $this->makeResponse([
                'data' => ['data' => $user->email],
                'message' => 'Password updated successfully',
                'status' => Response::HTTP_OK,
                'redirect' => 'base.user.show',
                'redirectParams' => ['id' => $user->id],
            ]);
        }, 'User not found', 'Could not update the password');
    }

    public function indexAddressesView($id)
    {
        return $this->processRequest(function () use ($id) {
            $user = User::findOrFail($id);

            return $this->makeResponse([
                'view' => 'base.user.index-addresses',
                'data' => ['data' => $user],
                'message' => 'Addresses edition view',
                'status' => Response::HTTP_OK,
            ]);
        }, 'User not found', 'Could not get the addresses edition view');
    }

    private function validatePassword(string $password, User $user, string $fieldName = 'password')
    {
        if (!Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([$fieldName => 'Por favor, verifique su contraseña.']);
        }
    }

    private function validateSelfProfile(){}
}
