<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AllUsersService;
use App\Mail\DailyWeatherForecast;
use Illuminate\Support\Facades\Mail;
use App\Services\WeatherForecastService;

class SendDailyWeatherForecastEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:weather';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily weather forecast via email';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(AllUsersService $allUsersService, WeatherForecastService $weatherForecastService)
    {
        $weatherForecastService->updateWeather();
        $data = $allUsersService->getAllUsers();

        foreach ($data as $key => $value) {
            $value['forecast'] = json_decode($value['forecast'], true);
            Mail::to($value['email'])->send(new DailyWeatherForecast($value));
        }
    }
}
