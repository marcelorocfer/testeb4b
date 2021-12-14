<?php

namespace App\Services;

use Throwable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class WeatherForecastService
{
    const LANG     = "pt_br";
    const UNITS    = "metric";
    const API_KEY  = '6788d05f2db19d2812252106d272afd3';
    const BASE_URL = "api.openweathermap.org/data/2.5/weather";

    public function __construct(User $user, Request $request)
    {
        $this->user = $user;
        $this->request = $request;
    }

    public function register()
    {
        try {
            $userData = $this->request->only('name', 'email');

            $ip = file_get_contents('https://api.ipify.org');
            $userData['ip_address'] = geoip()->getLocation($ip)['ip'];

            $location = geoip()->getLocation($userData['ip_address']);
            $response = Http::get(self::BASE_URL.'?q='.$location['city'].'&appid='.self::API_KEY.'&lang='.self::LANG.'&units='.self::UNITS);
            $userData['forecast'] = $response->body();

            $responseCode = json_decode($userData['forecast'], true);

            if ($responseCode['cod'] == '200') {
                if (!$this->user->create($userData)) {
                    abort(422, 'Error to create a new user!');
                }

                Log::info("Usuário Registrado!");
            } else {
                Log::error("Erro: ".$responseCode['message']);
                return response()->json([
                    'error' => $responseCode['message']
                ]);
            }

            unset($userData['password']);

            return response()->json([
                'data' => [
                    'user' => $userData
                ]
            ]);

        } catch (\Throwable $th) {
            Log::error("Erro ".$th);
            return response()->json([
                'error' => $th
            ]);
        }
    }

    public function updateWeather()
    {
        try {
            $users = $this->user->all();

            foreach ($users as $key => $value) {
                $ip = file_get_contents('https://api.ipify.org');

                $location = geoip()->getLocation($users[$key]['ip_address']);
                $response = Http::get(self::BASE_URL.'?q='.$location['city'].'&appid='.self::API_KEY.'&lang='.self::LANG.'&units='.self::UNITS);
                $value['forecast'] = $response->body();

                $responseCode = json_decode($value['forecast'], true);

                if ($responseCode['cod'] == '200') {
                    DB::table('users')
                        ->where('id', $value['id'])
                        ->where('ip_address', $value['ip_address'])
                        ->update(['forecast' => $value['forecast']]);
                } else {
                    Log::error("Erro: ".$responseCode['message']);
                    return response()->json([
                        'error' => $responseCode['message']
                    ]);
                }
            }

            return response()->json([
                'data' => [
                    'user' => $users
                ]
            ]);

            Log::info("Previsão do tempo atualizada!");

        } catch (\Throwable $th) {
            Log::error("Erro ".$th);
            return response()->json([
                'error' => $th
            ]);
        }
    }
}
