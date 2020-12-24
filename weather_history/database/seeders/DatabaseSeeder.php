<?php

namespace Database\Seeders;

use App\Models\Weather;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $today = Carbon::today();
        $last6Month = Carbon::today()->subMonths(6);

        do {
            $temp = rand(-40, 40);
            Weather::query()->create([
                'date_at' => $today->toDateString(),
                'temp' => $temp,
            ]);
            $today->subDay();
        } while ($last6Month <= $today);
    }
}
