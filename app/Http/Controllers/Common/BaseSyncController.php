<?php

namespace App\Http\Controllers\Common;

use App\Enums\DatabaseSync\Methods;
use App\Http\Controllers\Controller;
use App\Services\ApiClient;
use App\Traits\Helpers\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Nette\NotImplementedException;
use Symfony\Component\HttpFoundation\Response;

class BaseSyncController extends Controller
{
    use ResponseTrait;

    /**
     * @var ApiClient Service to interact with the Conspiracy API.
     */
    protected $apiClient;

    /**
     * @var string The base URL endpoint for the specific Conspiracy resource.
     */
    protected $baseUrl;

    /**
     * @var array The error list ocurred during syncing
     */
    protected array $errors;

    /**
     * @var array Values to send on the fetch data request
     */
    protected array $payload;

    /**
     * LiverpoolBaseController constructor.
     *
     * Initializes the controller with the Conspiracy API client service and
     * sets the base URL endpoint for the specific resource.
     *
     * @param string $baseUrl The base URL endpoint for the specific resource.
     */
    public function __construct(string $baseUrl, array $payload = [])
    {
        $this->apiClient = new ApiClient();
        $this->baseUrl = $baseUrl;
        $this->errors = [];
        $this->payload = $payload;
    }

    public function syncAllData(Request $request)
    {
        try {
            $data = $this->fetchData($request->page, $request->size, $this->payload);

            if ($data['success'] == false) {
                throw new Exception($data['message']);
            }

            DB::beginTransaction();

            $this->processData(collect($data['data']));

            DB::commit();

            return $this->makeResponse([
                'message' => 'Records synced successfully, ' . count($data['data']) . ' fetched records, ' . count($this->errors) . ' errors',
                'status' => Response::HTTP_OK,
                'forceJson' => true,
                'data' => [
                    'data' => $this->errors,
                ],
            ]);
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->makeResponse([
                'message' => 'Error syncing records',
                'success' => false,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'exception' => $exception,
                'forceJson' => true,
            ]);
        }
    }

    /**
     * Sends a request to the Conspiracy API and handles the response.
     *
     * Centralizes the logic for sending different types of HTTP requests to the Conspiracy API
     * and processes the responses.
     *
     * @param Methods $method The HTTP method.
     * @param array $payload The request data to be sent.
     * @param string $successMessage Default message for successful operations.
     * @return array The processed response.
     */
    private function sendRequest(Methods $method, array $payload, string $successMessage): array
    {
        try {
            $response = $this->apiClient->{$method->value}($this->baseUrl, $payload);

            // Check if the response is successful
            if (!$response->successful()) {
                throw new Exception($response->body());
            }

            // Decode the JSON response
            $data = $response->json();

            // Return the custom response
            return [
                'success' => true,
                'message' => $data['error'] ?? $successMessage,
                'data' => $data['data'] ?? [],
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Could not fetch data from remote',
                'data' => $e->getMessage(),
            ];
        }
    }

    /**
     * Fetches data from the Conspiracy API.
     *
     * @return array The fetched data or error message.
     */
    public function fetchData(int $page = null, int $size = null, array $payload = []): array
    {
        $payload = [
            ...compact('page', 'size'),
            ...$payload,
        ];

        return $this->sendRequest(Methods::GET, $payload, 'Data fetched successfully');
    }

    /**
     * Process the data fetched from the remote
     * 
     * @param Collection $data The fetched data to sync
     */
    protected function processData(Collection $data)
    {
        throw new NotImplementedException('This operation has not been implemented yet');
    }
}
