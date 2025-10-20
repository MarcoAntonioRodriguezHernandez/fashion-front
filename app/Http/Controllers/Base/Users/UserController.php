<?php

namespace App\Http\Controllers\Base\Users;

use App\Enums\CrudAction;
use App\Http\Controllers\Common\GenericCrudProvider;
use App\Http\Requests\Base\User\{
    PutRequest,
    PostRequest,
};
use App\Models\User;
use App\Models\Base\{
    ClientDetail,
    EmployeeDetail,
    Store,
};
use App\Traits\Base\User\HasAuthenticatable;
use App\Traits\Helpers\{
    HandlerFilesTrait,
    ResponseTrait,
};
use Exception;
use Illuminate\Database\Eloquent\{
    Builder,
    Model,
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Nette\NotImplementedException;

class UserController extends GenericCrudProvider
{

    // Common config
    use ResponseTrait, HandlerFilesTrait, HasAuthenticatable;

    protected string $modelClass = User::class;

    protected string $indexView = 'base.user.index';
    protected string $showView = 'base.user.show';
    protected string $editView = 'base.user.edit';

    protected ?string $searchField = 'email';

    protected function rules(): array
    {
        return [
            CrudAction::UPDATE->value => (new PutRequest())->rules(),
            CrudAction::CREATE->value => (new PostRequest())->rules(),
        ];
    }

    protected function createView()
    {
        throw new NotImplementedException(self::class . ' does not allow this operation');
    }

    protected function beforeCreate(array &$validatedData, Request $request): ?array
    {
        return [
            'password' => Hash::make(Str::before($validatedData['email'], '@')),
            'status' => true,
        ];
    }

    protected function beforeReadAll(Builder $query, Request $request): Builder
    {
        $userType = $request->route('userType');

        return $query->when($userType, fn($q) => $q->whereHas($userType . 'Detail'));
    }

    protected function beforeUpdate(array &$validatedData, Model $model, Request $request): ?array
    {
        $this->validateSelfProfile($model);

        return null;
    }

    protected function pushEditView(Model $model)
    {
        $this->validateSelfProfile($model);
        $stores = Store::all();
        $userType = null;
        if ($model->employeeDetail) {
            $userType = 'employee';
        } elseif ($model->clientDetail) {
            $userType = 'client';
        }
        return compact('stores', 'userType');
    }

    protected function afterCreate(Model $model, Request $request): ?array
    {
        if ($request->employee_detail) {
            $model->employeeDetail()->create([
                'store_id' => $request->employee_detail['store_id'],
            ]);
        }

        if ($request->client_detail) {
            $model->clientDetail()->create([
                'date_of_birth' => $request->client_detail['date_of_birth'],
                'gender' => $request->client_detail['gender'],
            ]);
        }

        if ($request->hasFile('photo')) {
            $model->update([
                'photo' => $this->upload($request->file('photo'), 'users'),
            ]);
        }

        return null;
    }

    protected function afterUpdate(Model $model, Request $request): ?array
    {
        if ($request->employee_detail) {
            EmployeeDetail::updateOrCreate([
                'user_id' => $model->id,
            ], [
                'store_id' => $request->employee_detail['store_id'],
            ]);
        }

        if ($request->client_detail) {
            ClientDetail::updateOrCreate([
                'user_id' => $model->id,
            ], [
                'date_of_birth' => $request->client_detail['date_of_birth'],
                'gender' => $request->client_detail['gender'],
            ]);
        }

        if ($request->hasFile('new_photo')) {
            try {
                $oldPhoto = $model->photo;
                $newPhoto = $this->upload($request->file('new_photo'), 'users');

                $model->update([
                    'photo' => $newPhoto,
                ]);

                if ($oldPhoto != null)
                    $this->deleteFileIfExists($oldPhoto);
            } catch (Exception $e) {
                if ($newPhoto)
                    $this->deleteFileIfExists($newPhoto);

                throw $e;
            }
        }

        return null;
    }

    /**
     * Get the dimensions of the image file, the firs element is the width and the second element is the height.
     * 
     * @return array The dimensions of the image
     */
    public function getImageDimensions(): array
    {
        return [900, 900];
    }

    protected function getIndexViewName()
    {
        if (request()->inertia() && request()->route()->getName() == 'base.user.create') {
            return null; // Force redirect back
        }

        return parent::getIndexViewName();
    }

    public function updateRecord(Request $request)
    {
        try {
            $validatedData = $request->validate($this->rules()[CrudAction::UPDATE->value]);

            $id = $request->input('id');
            $model = $this->getModelInstance()->findOrFail($id);

            $preUpdateResponse = $this->beforeUpdate($validatedData, $model, $request);
            if ($preUpdateResponse) {
                $validatedData = array_merge($validatedData, $preUpdateResponse);
            }

            $this->commitTransaction(function () use ($model, $validatedData) {
                $model->update($validatedData);
            });

            $this->afterUpdate($model, $request);
            $userType = null;
            if ($model->employeeDetail) {
                $userType = 'employee';
            } elseif ($model->clientDetail) {
                $userType = 'client';
            }
            return redirect()->route('base.user.index', ['userType' => $userType])
                ->with('success', $this->successMessages[CrudAction::UPDATE->value] ?? 'Usuario actualizado exitosamente');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {

            return back()->with('error', 'Usuario no encontrado.')->withInput();
        } catch (\Illuminate\Database\QueryException $exception) {

            return back()->with('error', 'Error en la base de datos al actualizar el usuario: ' . $exception->getMessage())->withInput();
        } catch (\Exception $exception) {

            return back()->with('error', 'Error al actualizar el usuario: ' . $exception->getMessage())->withInput();
        }
    }

    protected function beforeRead(Model $model): array
    {
        $userType = null;
        if ($model->employeeDetail) {
            $userType = 'employee';
        } elseif ($model->clientDetail) {
            $userType = 'client';
        }
        return ['userType' => $userType];
    }

    public function readAllRecords(Request $request)
    {
        try {
            $model = $this->getModelInstance();
            $perPage = (int) $request->get('per_page', 10);

            $currentFilters = $request->except(['page', 'per_page']);
            $previousFilters = session('previous_filters', []);
            if ($currentFilters != $previousFilters) {
                $request->merge(['page' => 1]);
            }
            session(['previous_filters' => $currentFilters]);

            $query = $model->newQuery();

            $searchTerm = '%' . $request->get($this->searchField) . '%';

            if ($this->searchField && $request->filled($this->searchField)) {
                $query->where($this->searchField, 'LIKE', $searchTerm);
            }

            if ($request->filled('search')) {
                $searchTerm = '%' . $request->search . '%';
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('name', 'like', $searchTerm)
                        ->orWhere('last_name', 'like', $searchTerm)
                        ->orWhere('email', 'like', $searchTerm);
                });
            }

            $userType = $request->route('userType');
            if (!$userType) {
                if (str_contains($request->url(), 'employees')) {
                    $userType = 'employee';
                } elseif (str_contains($request->url(), 'clients')) {
                    $userType = 'client';
                }
            }

            if ($userType === 'employee' && $request->filled('store_id')) {
                $query->whereHas('employeeDetail', fn($q) => $q->where('store_id', $request->store_id));
            }

            $this->beforeReadAll($query, $request);

            $data = $query->paginate($perPage)->appends($request->query());

            $postReadAllResponse = $this->afterReadAll($model);

            $postReadAllResponse['searchBy'] = $this->searchField;
            $postReadAllResponse['stores'] = Store::all();
            $postReadAllResponse['userType'] = $userType;

            return $this->makeResponse([
                'view' => $this->getIndexViewName(),
                'data' => [
                    'data' => $data,
                    ...$postReadAllResponse
                ],
                'message' => 'Usuarios obtenidos correctamente',
                'status' => \Symfony\Component\HttpFoundation\Response::HTTP_OK,
            ]);
        } catch (\Exception $e) {
            return $this->makeResponse([
                'message' => 'Error al obtener usuarios',
                'error' => $e->getMessage(),
                'status' => \Symfony\Component\HttpFoundation\Response::HTTP_INTERNAL_SERVER_ERROR,
            ]);
        }
    }
}
