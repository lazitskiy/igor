<?php
declare(strict_types=1);

namespace App\FormRequest;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class WeatherGetByDateFormRequest extends FormRequest
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
