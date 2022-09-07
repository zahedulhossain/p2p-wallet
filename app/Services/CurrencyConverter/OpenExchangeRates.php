<?php

namespace App\Services\CurrencyConverter;

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

    public function convert($amount, $from, $to): array
    {
        $responseArr = $this->getLatestRates($from, $to);

        if (isset($responseArr['rates'], $responseArr['rates'][$to])) {
            return [
                'conversion_rate' => $responseArr['rates'][$to],
                'converted_amount' => $amount * $responseArr['rates'][$to]
            ];
        }

        return [
            'conversion_rate' => null,
            'converted_amount' => null
        ];
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
            'symbols' => $convertedCurrencyCode
        ];

        $response = Http::withHeaders($headers)->get($url, $query);

        if (!$response->successful()) {
            abort(Response::HTTP_BAD_GATEWAY, 'Sorry! Currency conversion is unavailable at the moment.');
        }

        return $response->json() ?: [];
    }
}
