<?php

namespace App\Http\Controllers\Common;

use App\Enums\Auth\PermissionTypes;
use App\Enums\CrudAction;
use App\Http\Controllers\Controller;
use App\Traits\Base\Permission\NeedsPermissions;
use App\Traits\Helpers\ResponseTrait;
use Illuminate\Database\Eloquent\{
    Builder,
    Model,
    ModelNotFoundException,
};
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

abstract class GenericCrudProvider extends Controller
{

    // COMMON CONFIG
    use ResponseTrait, NeedsPermissions;

    // ENTITY CLASS
    protected string $modelClass;

    // WEB VIEWS
    protected string $indexView;
    protected string $showView;
    protected string $createView;
    protected string $editView;

    // SUPPORT PROPERTIES
    protected int $pageSize;
    protected ?string $searchField = null;

    public function __construct()
    {
        $this->pageSize = $this->pageSize ?? config('common.pagination_limit');
    }

    protected function commitTransaction(callable $operation)
    {
        try {
            $result = $operation();

            return $result;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    abstract protected function rules(): array;

    protected function getModelInstance(): Model
    {
        return app($this->modelClass);
    }

    protected function createView()
    {
        return $this->makeResponse([
            'view' => $this->createView,
            'data' => ["data" => collect($this->pushCreateView() ?? null)],
            'message' => 'Records retrieved successfully',
            'status' => Response::HTTP_OK,
        ]);
    }

    protected function pushCreateView()
    {
        return null;
    }

    // CREATE RECORD

    protected function createRecord(Request $request)
    {
        $validatedData = $request->validate($this->rules()[CrudAction::CREATE->value]);

        // Pre-Create Hook: To handle logic before creation, such as uploading a picture
        $preCreateResponse = $this->beforeCreate($validatedData, $request);
        if ($preCreateResponse)
            $validatedData = array_merge($validatedData, $preCreateResponse);

        try {
            $result = $this->commitTransaction(fn() => ($this->getModelInstance())->create($validatedData));

            // Post-Create Hook: To handle logic after creation, such as interactions with external APIs
            $redirectParams = $this->afterCreate($result, $request) ?? [];

            $result = $result->withoutRelations();

            return $this->makeResponse([
                'data' => ['data' => $result],
                'message' => 'Record created successfully',
                'status' => Response::HTTP_CREATED,
                'redirect' => $this->getIndexViewName(),
                'redirectParams' => $redirectParams,
            ]);
        } catch (\Exception $exception) {
            return $this->makeResponse([
                'message' => 'Failed to create record',
                'success' => false,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'exception' => $exception,
            ]);
        }
    }

    protected function beforeCreate(array &$validatedData, Request $request): ?array
    {
        // Here you can handle pre-creation logic, such as uploading files
        // Return an array with additional data to mix with $validatedData if needed
        return null;
    }

    protected function afterCreate(Model $model, Request $request): ?array
    {
        // Here you can handle post-creation logic, such as interacting with external APIs
        // If any value returned, will be taken as redirect params to index view
        return null;
    }

    // READ RECORD
    protected function readRecord($field)
    {
        try {
            $model = $this->getModel($field);

            // Here you could implement beforeRead if necessary
            $preReadAllResponse = collect($this->beforeRead($model));

            // Assuming you want to pass the model to the view or do something before sending the response.
            $this->afterRead($model);

            return $this->makeResponse([
                'view' => $this->showView,
                'data' => ["data" => $model, ...$preReadAllResponse],
                'message' => 'Record retrieved successfully',
                'status' => Response::HTTP_OK,
            ]);
        } catch (ModelNotFoundException $exception) {
            return $this->makeResponse([
                'message' => 'Record not found',
                'success' => false,
                'status' => Response::HTTP_NOT_FOUND,
            ]);
        } catch (\Exception $exception) {
            return $this->makeResponse([
                'message' => 'Error retrieving record',
                'success' => false,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'exception' => $exception,
            ]);
        }
    }

    protected function beforeRead(Model $model)
    {
        // Logic before reading the log, if necessary.
        return null;
    }

    protected function afterRead(Model $model)
    {
        // Logic after reading the log, if necessary.
        // For example, you could trigger events or do some kind of transformation.
    }

    // READ ALL RECORDS
    protected function readAllRecords(Request $request)
    {
        try {
            $model = $this->getModelInstance();

           
            // Getting all data collection in ascending order
            $data = $model->query()
                ->when($this->searchField && $request->filled($this->searchField), fn($q) => $q->where($this->searchField, 'LIKE', $request->get($this->searchField)))
                ->tap(fn($q) => $this->beforeReadAll($q, $request))
                ->paginate($this->pageSize);


            // Assuming you want to pass the model to the view or do something before sending the response.
            $postReadAllResponse = $this->afterReadAll($model);

            if ($this->searchField) {
                $postReadAllResponse['searchBy'] = $this->searchField;
            }

            return $this->makeResponse([
                'view' => $this->getIndexViewName(),
                'data' => ["data" => $data, ...$postReadAllResponse],
                'message' => 'Records retrieved successfully',
                'status' => Response::HTTP_OK,
            ]);
        } catch (\Exception $exception) {
            return $this->makeResponse([
                'message' => 'Error retrieving records',
                'success' => false,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'exception' => $exception,
            ]);
        }
    }

    protected function beforeReadAll(Builder $query, Request $request): Builder
    {
        // Logic before reading the log, if necessary.
        return $query->oldest();
    }

    protected function afterReadAll(Model $model): array
    {
        // Logic after reading the log, if necessary.
        // For example, you could trigger events or do some kind of transformation.
        return [];
    }

    protected function editView($field)
    {
        try {

            $data = $this->getModel($field);

            return $this->makeResponse([
                'view' => $this->editView,
                'data' => ["data" => $data, ...collect($this->pushEditView($data))],
                'message' => 'Records retrieved successfully',
                'status' => Response::HTTP_OK,
            ]);
        } catch (ModelNotFoundException $exception) {
            return $this->makeResponse([
                'message' => 'Record not found',
                'success' => false,
                'status' => Response::HTTP_NOT_FOUND,
            ]);
        } catch (\Exception $exception) {
            return $this->makeResponse([
                'message' => 'Error retrieving record',
                'success' => false,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'exception' => $exception,
            ]);
        }
    }

    protected function pushEditView(Model $model)
    {
        return null;
    }

    // UPDATE RECORD
    protected function updateRecord(Request $request)
    {
        try {
            $validatedData = $request->validate($this->rules()[CrudAction::UPDATE->value]);

            $model = $this->getModel($request->id);

            // Pre-Update Hook
            $preUpdateResponse = $this->beforeUpdate($validatedData, $model, $request);
            if ($preUpdateResponse)
                $validatedData = array_merge($validatedData, $preUpdateResponse);

            $this->commitTransaction(function () use ($model, $validatedData) {
                $model->update($validatedData);
            });

            // Post-Update Hook
            $redirectParams = $this->afterUpdate($model, $request) ?? [];

            $model = $model->withoutRelations();

            return $this->makeResponse([
                'data' => ['data' => $model],
                'message' => 'Record updated successfully',
                'status' => Response::HTTP_OK,
                'redirect' => $this->getIndexViewName(),
                'redirectParams' => $redirectParams,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
            return $this->makeResponse([
                'message' => 'Record not found',
                'success' => false,
                'status' => Response::HTTP_NOT_FOUND,
                'exception' => $exception,
            ]);
        } catch (QueryException $exception) {
            return $this->makeResponse([
                'message' => 'Database error',
                'success' => false,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'exception' => $exception,
            ]);
        } catch (\Exception $exception) {
            return $this->makeResponse([
                'message' => 'Failed to update record',
                'success' => false,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'exception' => $exception,
            ]);
        }
    }

    protected function beforeUpdate(array &$validatedData, Model $model, Request $request): ?array
    {
        // Logic prior to upgrade, if necessary.
        // For example, you could handle updating an image here.
        // Returns an array with additional data to mix with $validatedData if necessary.
        return null;
    }

    protected function afterUpdate(Model $model, Request $request): ?array
    {
        // Logic after upgrade, if necessary.
        // For example, you could trigger events here or perform post-processing.
        // If any value returned, will be taken as redirect params to index view
        return null;
    }

    // DELETE RECORD
    protected function deleteRecord($field)
    {
        try {
            $model = $this->getModel($field);

            // Pre-Delete Hook: Here you can include logic before deletion, such as deleting associated files.
            $this->beforeDelete($model);

            $this->commitTransaction(function () use ($model) {
                $model->delete();

                $this->whileDelete($model);
            });

            // Post-Delete Hook: for any logic after deletion, if required
            $this->afterDelete($model);

            return $this->makeResponse([
                'message' => 'Record deleted successfully',
                'status' => Response::HTTP_OK,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
            return $this->makeResponse([
                'message' => 'Record not found',
                'success' => false,
                'status' => Response::HTTP_NOT_FOUND,
            ]);
        } catch (\Exception $exception) {
            return $this->makeResponse([
                'message' => 'Failed to delete record',
                'success' => false,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'exception' => $exception,
            ]);
        }
    }

    protected function beforeDelete(Model $model)
    {
        //  Optional logic before deleting the record.
    }

    protected function whileDelete(Model $model)
    {
        // Optional logic after deleting and before committing the transaction.
    }

    protected function afterDelete(Model $model)
    {
        // Optional logic after deleting the record.
    }

    protected function getModel(int | string $field): Model
    {
        return is_numeric($field) ? $this->getModelInstance()->findOrFail($field) : $this->getModelInstance()->where('slug', $field)->firstOrFail();
    }

    protected function processRequest(callable $process, string $notFoundMessage, string $exceptionMessage)
    {
        try {
            return $process();
        } catch (ModelNotFoundException $exception) {
            return $this->makeResponse([
                'message' => $notFoundMessage,
                'success' => false,
                'status' => Response::HTTP_NOT_FOUND,
                'exception' => $exception,
            ]);
        } catch (\Exception $exception) {
            return $this->makeResponse([
                'message' => $exceptionMessage,
                'success' => false,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'exception' => $exception,
            ]);
        }
    }

    protected function getIndexViewName()
    {
        return $this->currentUserHasPermission(PermissionTypes::READ) ? $this->indexView : 'dashboard';
    }
}
