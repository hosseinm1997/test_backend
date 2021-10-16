<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Infrastructure\Service\JsonResponseTransformer;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class RespondJsonMiddleware
{

    /** @var \Illuminate\Http\Response */
    protected $response;

    /**
     * Routes that should skip handle.
     *
     * @var array
     */
    protected $exceptRouteNames = [
        'export.test',
        'sheba.bank.file'
    ];

    public function handle(Request $request, Closure $next = null)
    {

        if ($next instanceof Closure)
            $response = $next($request);
        else
            $response = $request;

//        if ($response instanceof BinaryFileResponse) {
//            return $response;
//        }

        if (
            !in_array($request->route()->getName(), $this->exceptRouteNames)
            && (config('app.debug') && ($request->debug || $request->header('debug') == true) )
        ) {
            $response = (new JsonResponseTransformer())->transform($response->getContent(), $response->exception);
        }

        return $response;
    }

}
