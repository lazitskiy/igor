<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\JsonRpcFormRequest\WeatherGetByDateJsonRpcFormRequest;
use App\JsonRpcFormRequest\WeatherGetHistoryJsonRpcFormRequest;
use App\Services\WeatherService;

class WeatherController extends Controller
{
    private WeatherService $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function getByDate(WeatherGetByDateJsonRpcFormRequest $formRequest)
    {
        $weather = $this->weatherService->getByDate($formRequest->getDate());
        if ($weather) {
            return $weather->toJsonResponse();
        }
    }

    public function getHistory(WeatherGetHistoryJsonRpcFormRequest $formRequest)
    {
        $weatherCollection = $this->weatherService->getHistory($formRequest->getLastDays());

        if ($weatherCollection->isNotEmpty()) {
            return $weatherCollection->toJsonResponse();
        }
    }
}
