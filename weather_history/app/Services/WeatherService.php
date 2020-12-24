<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Weather;
use App\Models\WeatherCollection;
use App\Models\WeatherQueryBuilder;
use Carbon\Carbon;

/**
 * @method static WeatherQueryBuilder query()
 */
class WeatherService
{
    public function getByDate(Carbon $date): ?Weather
    {
        return Weather::query()->scopeByDate($date)->first();
    }

    public function getHistory(int $lastDays): WeatherCollection
    {
        return Weather::query()->scopeByLastDays($lastDays)->get();
    }
}
