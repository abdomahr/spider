<?php

use Illuminate\Foundation\{
    Configuration\Exceptions,
    Configuration\Middleware,
    Application
};
use App\Enums\ResponseMethodEnum;
use Illuminate\Auth\AuthenticationException;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\{
    MethodNotAllowedHttpException,
    NotFoundHttpException,
    UnauthorizedHttpException
};

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            return generalApiResponse(ResponseMethodEnum::CUSTOM, customMessage: __('This page is not found'), customStatusMsg: 'fail', customStatus: 404);
        });

        $exceptions->render(function (ModelNotFoundException $e, Request $request) {
            return generalApiResponse(ResponseMethodEnum::CUSTOM, customMessage: __('This page is not found'), customStatusMsg: 'fail', customStatus: 404);
        });
        $exceptions->render(function (UnauthorizedHttpException $e, Request $request) {
            return generalApiResponse(ResponseMethodEnum::CUSTOM, customMessage: __('You are not authorized'), customStatusMsg: 'fail', customStatus: 404);
        });

        $exceptions->render(function (MethodNotAllowedHttpException $e, Request $request) {
            return generalApiResponse(ResponseMethodEnum::CUSTOM, customMessage: __('The method ' . request()->method() . ' is not allowed for this route'), customStatusMsg: 'fail', customStatus: 405);
        });

        $exceptions->render(function (AuthenticationException $e, Request $request) {
            return generalApiResponse(ResponseMethodEnum::CUSTOM, customMessage: __('You are not authenticated'), customStatusMsg: 'fail', customStatus: 401);
        });
    })->create();
