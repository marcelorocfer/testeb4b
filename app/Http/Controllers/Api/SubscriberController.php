<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\WeatherForecastService;

class SubscriberController extends Controller
{
    public function __construct(WeatherForecastService $weatherForecastService)
    {
        $this->weatherForecastService = $weatherForecastService;
    }

    public function subscribe()
    {
        return $this->weatherForecastService->register();
    }

    public function update()
    {
        return $this->weatherForecastService->updateWeather();
    }
}
