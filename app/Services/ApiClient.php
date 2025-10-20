<?php

namespace App\Services;

use App\Enums\DatabaseSync\Methods;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

/**
 * Class ApiClient
 *
 * This service is responsible for making requests to the Conspiracy API.
 */
class ApiClient
{

    /**
     * Make a request to the API with an authentication token.
     *
     * If a 401 status (unauthorized) is returned, the method will re-authenticate
     * and make the request again.
     *
     * @param Methods $method HTTP method.
     * @param string $url Endpoint URL.
     * @param array $options Additional request options.
     * @return Response|\Exception
     */
    private function makeRequest(Methods $method, string $url, array $options = []): Response
    {
        return Http::withOptions([
            'timeout' => 60,
        ])->withHeaders([
            'Accept' => 'application/json',
        ])->{$method->value}($url, $options);
    }

    /**
     * Make a GET request to the Liverpool API.
     *
     * @param string $url Endpoint URL.
     * @param array $params Request parameters.
     * @param array $headers Additional headers.
     * @return Response|\Exception
     */
    public function get(string $url, array $params = [], array $headers = []): Response
    {
        // Combine parameters and headers
        $options = array_merge($params, $headers);

        return $this->makeRequest(Methods::GET, $url, $options);
    }

    /**
     * Make a POST request to the Liverpool API.
     *
     * @param string $url Endpoint URL.
     * @param array $data Request payload/data.
     * @param array $headers Additional headers.
     * @return Response|\Exception
     */
    public function post(string $url, array $data = [], array $headers = []): Response
    {
        // Combine data and headers
        $options = array_merge($data, $headers);

        return $this->makeRequest(Methods::POST, $url, $options);
    }

    /**
     * Make a PATCH request to a specified URL.
     *
     * This method sends a PATCH request to the provided URL with the given data and headers.
     * It utilizes the `makeRequest` method to ensure that the request is authenticated.
     *
     * @param string $url The endpoint URL for the request.
     * @param array $data The payload/data for the PATCH request.
     * @param array $headers Additional headers for the request.
     * @return Response
     */
    public function patch(string $url, array $data = [], array $headers = []): Response
    {
        // Combine data and headers to create the request options.
        $options = array_merge($data, $headers);

        // Send the PATCH request with authentication token and return the response.
        return $this->makeRequest(Methods::PATCH, $url, $options);
    }

    /**
     * Send a request to delete a resource in the Liverpool API.
     *
     * While typically deletions use the DELETE HTTP method, the Liverpool API might expect
     * a PATCH request to a specific '/delete' endpoint for this action.
     *
     * @param string $url     The base endpoint URL for the delete action.
     * @param array  $data    The payload/data for the request.
     * @param array  $headers Additional headers for the request.
     * @return Response
     */
    public function delete(string $url, array $data = [], array $headers = []): Response
    {
        // Combine data and headers to create the request options.
        $options = array_merge($data, $headers);

        // Send the PATCH request with an authentication token and return the response.
        return $this->makeRequest(Methods::DELETE, $url, $options);
    }
}
