<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class WeatherService
{
    // The weather for the dashboards: cached for 30 minutes, with the last good reading as a backup
    public function current(): ?array
    {
        $data = Cache::remember('weather.current', now()->addMinutes(30), fn () => $this->fetch());

        // If the API failed (null), show the last saved reading, if there is one
        return $data ?? Cache::get('weather.last');
    }

    // Calls Open-Meteo. Returns null on any problem, so the dashboard never breaks
    private function fetch(): ?array
    {
        try {
            $response = Http::timeout(3)->get(config('services.weather.url'), [
                'latitude'  => config('services.weather.latitude'),
                'longitude' => config('services.weather.longitude'),
                'timezone'  => 'Asia/Manila',
                'current'   => 'temperature_2m,relative_humidity_2m,wind_speed_10m,weather_code',
            ]);
        } catch (ConnectionException $e) {
            return null;
        }

        if ($response->failed()) {
            return null;
        }

        $current = $response->json('current');

        if (!is_array($current)) {
            return null;
        }

        Cache::put('weather.last', $current, now()->addDay());

        return $current;
    }

    // Turns a WMO weather code into simple text
    public function label(?int $code): string
    {
        return match (true) {
            $code === 0 => 'Clear',
            in_array($code, [1, 2, 3], true) => 'Partly Cloudy',
            in_array($code, [45, 48], true) => 'Fog',
            in_array($code, [51, 53, 55, 61, 63, 65], true) => 'Rain',
            in_array($code, [80, 81, 82], true) => 'Rain Showers',
            in_array($code, [95, 96, 99], true) => 'Thunderstorm',
            default => 'Unknown',
        };
    }
}
