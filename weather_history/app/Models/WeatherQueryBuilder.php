<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\Base\BuilderBase;
use Carbon\Carbon;

/**
 * @method Weather|null first($columns = ['*'])
 * @method WeatherCollection get($columns = ['*'])
 */
class WeatherQueryBuilder extends BuilderBase
{
    public function scopeByDate(Carbon $date): self
    {
        return $this->whereDate('date_at', $date);
    }

    public function scopeByLastDays(int $lastDays): self
    {
        $dateFrom = Carbon::now()->subDays($lastDays);

        return $this->whereDate('date_at', '>', $dateFrom)->orderByDesc('date_at');
    }
}
