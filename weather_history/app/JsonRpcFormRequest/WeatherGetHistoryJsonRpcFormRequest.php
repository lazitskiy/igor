<?php
declare(strict_types=1);

namespace App\JsonRpcFormRequest;

use Carbon\Carbon;
use Upgate\LaravelJsonRpc\Server\FormRequest;

class WeatherGetHistoryJsonRpcFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'lastDays' => ['bail', 'required', 'numeric', 'min:1', 'max:30'],
        ];
    }

    public function getLastDays(): int
    {
        return $this->get('lastDays');
    }
}
