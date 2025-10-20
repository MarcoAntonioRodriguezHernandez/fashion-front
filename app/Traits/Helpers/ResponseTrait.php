<?php

namespace App\Traits\Helpers;

use App\Services\DiscordBotService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

trait ResponseTrait
{
    /**
     * Return an appropriate response.
     *
     * @param array $options
     * @return \Illuminate\Http\Response|JsonResponse
     */
    public function makeResponse(array $options = [])
    {
        // Get the options.
        $data = $options['data'] ?? null;
        $view = $options['view'] ?? null;
        $message = $options['message'] ?? '';
        $success = $options['success'] ?? true;
        $status = $this->makeStatusCode($options['status'] ?? null, $success);
        $exception = $options['exception'] ?? null;
        $redirectRoute = $options['redirect'] ?? null;
        $redirectParams = $options['redirectParams'] ?? null;
        $channel = $this->makeLogChannel($options['type'] ?? null, $success); // Pass the type because will be usually the same as channel
        $type = $this->makeLogType($options['type'] ?? null, $success);
        $errorRoute = static::class . '::' . ((new Exception())->getTrace()[1]['function']);
        $forceJson = $options['forceJson'] ?? false;

        $exceptionMessage = $exception?  (is_string($exception) ? $exception : $exception->getMessage()) : null;

        // Log the message.
        Log::channel($channel)->$type($message . ' | ' . $errorRoute, [
            'Status' => ($success ? 'Success' : 'Error'),
            'Data' => ($success ? $data : $exceptionMessage),
        ]);

        // Prepare the response data.
        $responseData = [
            'status' => $success ? 'success' : 'error',
            'code_status' => $status,
            'message' => $message,
        ];

        // Add the data and quantity to the response if provided.
        if ($data) {
            $responseData['data_count'] = $this->getCountFromData($data['data']);
            $responseData['responses_count'] = $this->getCountFromData($data);

            $responseData = array_merge($responseData, $data);
        }

        // Add the error to the data if an exception was passed.
        if (!$success && $exception) {
            $responseData['data'] = ['error' => $exceptionMessage];

            DiscordBotService::errorLog($message, $exceptionMessage, $errorRoute);
        }

        // If the request expects JSON, return a JSON response.
        if ($forceJson || request()->expectsJson())
            return response()->json($responseData, $status);

        // If not expecting JSON, return a redirect or a view.
        if ($success) {
            if ($view) {
                $names = array_keys($data);

                extract($data);

                return view($view, compact($names))->with('success', $message);
            } else
                return $redirectRoute
                    ? redirect()->route($redirectRoute, $redirectParams)->with('success', $message)->with('data', $data)
                    : redirect()->back()->with('success', $message)->with('data', $data);
        } else {
            $errorMessage = !$exception ? $message : ($message . ': ' . $exceptionMessage);

            if ($view)
                return view($view, compact('data'))->withErrors($errorMessage);
            else
                return $redirectRoute
                    ? redirect()->route($redirectRoute, $redirectParams)->withErrors($errorMessage)->withInput()
                    : redirect()->back()->withErrors($errorMessage)->withInput();
        }
    }

    /**
     * Validate and get the status code if not defined
     *
     * @param string $statusCode Value to check. If null, will be set the default
     * @param bool $success If the operation was successful or not
     *
     * @return string The status code
     */
    private function makeStatusCode(int $statusCode = null, bool $success = true)
    {
        if ($statusCode !== null && !array_key_exists($statusCode, Response::$statusTexts)) // If the status code is provided but not valid
            throw new InvalidArgumentException('The status code [' . $statusCode . '] is not supported');

        if (!$success && !$statusCode) return Response::HTTP_INTERNAL_SERVER_ERROR;

        return $statusCode ?? Response::HTTP_OK;
    }

    /**
     * Validate and get the log channel if not defined
     *
     * @param string $logChannel Value to check. If null, will be set the default
     * @param bool $success If the operation was successful or not
     *
     * @return string The log channel
     */
    private function makeLogChannel(string $logChannel = null, bool $success = true)
    {
        if ($logChannel !== null && !in_array($logChannel, array_keys(config('logging.channels')))) // If the log channel is provided but not valid
            throw new InvalidArgumentException('The log channel [' . $logChannel . '] is not supported');

        if ($logChannel !== null) // Valid log channel
            return $logChannel;
        else
            return $success ? 'info' : 'fatal';
    }

    /**
     * Validate and get the log type if not defined
     *
     * @param string $logType Value to check. If null, will be set the default
     * @param bool $success If the operation was successful or not
     *
     * @return string The log type
     */
    private function makeLogType(string $logType = null, bool $success = true)
    {
        if ($logType !== null && !in_array($logType, ['emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug'])) // If the log type is provided but not valid
            throw new InvalidArgumentException('The log type [' . $logType . '] is not supported');

        if ($logType !== null) // Valid log type
            return $logType;
        else
            return $success ? 'debug' : 'error';
    }

    /**
     * Get the count from the data provided.
     *
     * @param mixed $data The data from which to get the count.
     * @return int The count of items.
     */
    private function getCountFromData($data): int
    {
        // If $data is a collection, use count()
        if ($data instanceof \Illuminate\Support\Collection)
            return $data->count();

        // If $data is a single model, returns 1
        if ($data instanceof \Illuminate\Database\Eloquent\Model)
            return 1;

        // If it is neither a model nor a collection, it is possible that it is an array or null.
        if (is_array($data))
            return count($data);

        // If $data is null or any other data type, returns 0
        return 0;
    }
}
