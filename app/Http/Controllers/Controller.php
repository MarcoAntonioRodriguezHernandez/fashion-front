<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\{
    JsonResponse,
    RedirectResponse,
};
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Execute an action on the controller.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function callAction($method, $parameters)
    {
        DB::beginTransaction();

        try {
            $result = $this->{$method}(...array_values($parameters));

            if ($result instanceof JsonResponse && $result->getData()->status != 'success')
                throw new \Exception($result->getData()->message);
            elseif ($result instanceof RedirectResponse && $result->getSession()->has('errors'))
                throw new \Exception($result->getSession()->get('errors')->getMessages()[0][0]);

            DB::commit();

            return $result;
        } catch (\Exception | \Error $exception) {
            DB::rollBack();

            if (isset($result))
                return $result;

            throw $exception;
        }
    }
}
