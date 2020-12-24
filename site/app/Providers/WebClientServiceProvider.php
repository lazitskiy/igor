<?php

namespace App\Providers;

use App\Http\WebClients\Weather\WeatherWebClient;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class WebClientServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(WeatherWebClient::class, function () {

            $apiHost = env('WEATHER_API_HOST');
            $httpClient = app(Client::class);

            return new WeatherWebClient($apiHost, $httpClient);
        });
    }
}
