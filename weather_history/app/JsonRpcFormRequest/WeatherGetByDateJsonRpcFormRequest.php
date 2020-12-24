<?php
declare(strict_types=1);

namespace App\JsonRpcFormRequest;

use Carbon\Carbon;
use Upgate\LaravelJsonRpc\Server\FormRequest;

class WeatherGetByDateJsonRpcFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'date' => ['bail', 'required', 'date', 'date_format:Y-m-d'],
        ];
    }

    public function getDate(): Carbon
    {
        return Carbon::parse($this->get('date'));
    }
}
