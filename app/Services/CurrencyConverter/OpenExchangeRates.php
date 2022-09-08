<?php

namespace App\Services\CurrencyConverter;

use App\Values\ConvertedMoney;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class OpenExchangeRates implements CurrencyConverter
{
    protected string $url;
    protected string $appId;

    public function __construct()
    {
        $this->url = rtrim(config('services.openexchangerates.url'), '/');
        $this->appId = config('services.openexchangerates.app_id');
    }

    public function convert($amount, $from, $to): ConvertedMoney
    {
        $responseArr = $this->getLatestRates($from, $to);

        if (isset($responseArr['rates'], $responseArr['rates'][$to])) {
            return ConvertedMoney::make($amount * $responseArr['rates'][$to], $responseArr['rates'][$to]);
        }

        return ConvertedMoney::make();
    }

    public function getLatestRates($baseCurrencyCode, $convertedCurrencyCode)
    {
        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Token ' . $this->appId,
        ];

        $url = "{$this->url}/api/latest.json";
        $query = [
            'base' => $baseCurrencyCode,
            'symbols' => $convertedCurrencyCode,
        ];

        $response = Http::withHeaders($headers)->get($url, $query);

        if (!$response->successful()) {
            abort(Response::HTTP_BAD_GATEWAY, 'Sorry! Currency conversion is unavailable at the moment.');
        }

        return $response->json() ?: [];
    }
}
