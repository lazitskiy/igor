<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\Base\ModelBase;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property Carbon $date_at
 * @property float $temp
 *
 * @method static WeatherQueryBuilder query()
 */
class Weather extends ModelBase
{
    use HasFactory;

    public $timestamps = false;

    protected $casts = [
        'date_at' => 'date',
    ];

    public function toJsonResponse()
    {
        return [
            'date_at' => $this->date_at->toDateString(),
            'temp' => $this->temp,
        ];
    }
}
