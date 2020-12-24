<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\FormRequest\WeatherGetByDateFormRequest;
use App\Http\WebClients\Weather\WeatherWebClient;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class IndexController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index(WeatherWebClient $client)
    {
        $last30DaysWeather = [];
        $apiErrors = [];
        try {
            $last30DaysWeather = $client->getHistory();
        } catch (\Exception $e) {
            $apiErrors['getHistory'] = $e->getMessage();
        }

        return view('welcome', [
            'weather' => null,
            'last30DaysWeather' => $last30DaysWeather,
            'apiErrors' => $apiErrors,
        ]);
    }

    public function getByDate(WeatherWebClient $client, WeatherGetByDateFormRequest $request)
    {
        $apiErrors = [];
        $last30DaysWeather = [];

        try {
            $weather = $client->getByDate($request->getDate());
        } catch (\Exception $e) {
            $weather['date_at'] = $request->getDate()->toDateString();
            $apiErrors['getByDate'] = 'Нет данных по погоде';
        }

        try {
            $last30DaysWeather = $client->getHistory();
        } catch (\Exception $e) {
            $apiErrors['getHistory'] = $e->getMessage();
        }

        return view('welcome', [
            'weather' => $weather,
            'last30DaysWeather' => $last30DaysWeather,
            'apiErrors' => $apiErrors,
        ]);
    }
}
