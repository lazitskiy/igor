<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use App\Http\Controllers\WeatherController;
use Upgate\LaravelJsonRpc\Contract\ServerInterface;
use Upgate\LaravelJsonRpc\Contract\ServerInterface as JsonRpcServerContract;

$router->get('/', function () use ($router) {
    return $router->app->version();
});


$router->post('/jsonrpc', function (Illuminate\Http\Request $request) {
    /** @var ServerInterface $jsonRpcServer */
    $jsonRpcServer = app(JsonRpcServerContract::class);
    $jsonRpcServer->router()
        ->bindController('weather', WeatherController::class);

    return $jsonRpcServer->run($request);
});
