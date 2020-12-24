<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;

/**
 * @method Weather|null first($columns = ['*'])
 */
class WeatherCollection extends Collection
{
    public function toJsonResponse()
    {
        return $this->map(function (Weather $weather) {
            return $weather->toJsonResponse();
        });
    }
}
