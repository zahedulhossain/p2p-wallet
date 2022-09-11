<?php

namespace App\Services\CurrencyConverter;

use App\Data\ConvertedMoney;
use App\Exceptions\MissingEnvVariableException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class OpenExchangeRates implements CurrencyConverter
{
    private string $url;
    private string $appId;

    public function __construct()
    {
        if (!$this->isEnvSpecified()) {
            throw new MissingEnvVariableException();
        }

        $this->url = rtrim(config('services.openexchangerates.url'), '/');
        $this->appId = config('services.openexchangerates.app_id');
    }

    public function convert(float $amount, string $from, string $to): ConvertedMoney
    {
        $responseArr = $this->getLatestRates($from, $to);

        if (isset($responseArr['rates'], $responseArr['rates'][$to])) {
            return new ConvertedMoney($amount * $responseArr['rates'][$to], $responseArr['rates'][$to]);
        }

        return new ConvertedMoney();
    }

    /**
     * @param string $baseCurrencyCode
     * @param string $convertedCurrencyCode
     * @return array<string, string|array<string, float>>
     */
    public function getLatestRates(string $baseCurrencyCode, string $convertedCurrencyCode): array
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
        logger($response->json());

        if (!$response->successful()) {
            abort(Response::HTTP_BAD_GATEWAY, 'Sorry! Currency conversion is unavailable at the moment.');
        }

        return $response->json() ?: [];
    }

    private function isEnvSpecified(): bool
    {
        if (app()->environment('testing')) {
            return true;
        }

        if (config('services.openexchangerates.url') && config('services.openexchangerates.app_id')) {
            return true;
        }

        return false;
    }
}
