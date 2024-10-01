<?php

use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
         // 404
         $exceptions->render(function(NotFoundHttpException $error, Request $request){
            if($request->is("api/*")){
                return response()->json([
                    "message"=> "Page not Found !404"
                ],404);
            }
        });

        $exceptions->render(function(Exception $error){
           return response()->json([
            "errors" =>$error->getMessage()
           ],422);
        });
    })->create();
