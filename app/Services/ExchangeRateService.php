<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExchangeRateService
{
    private $apiUrl;
    private $cacheMinutes;

    public function __construct()
    {
        $this->apiUrl = config('services.exchange_rate.api_url', 'https://api.dolarvzla.com/public/exchange-rate');
        $this->cacheMinutes = config('services.exchange_rate.cache_minutes', 30);
    }

    public function getCurrentRate()
    {
        return Cache::remember('exchange_rate', $this->cacheMinutes, function () {
            try {
                $response = Http::timeout(10)->get($this->apiUrl);
                
                if ($response->successful()) {
                    return $response->json();
                }
                
                Log::warning('Exchange rate API returned error', ['status' => $response->status()]);
                return null;
                
            } catch (\Exception $e) {
                Log::error('Failed to fetch exchange rate', ['error' => $e->getMessage()]);
                return null;
            }
        });
    }

    public function getFormattedRate()
    {
        $data = $this->getCurrentRate();
        
        if (!$data || !isset($data['current'])) {
            return [
                'rate' => 'N/A',
                'change' => '0.00',
                'date' => 'N/A',
                'isPositive' => true
            ];
        }

        $current = $data['current'];
        $changePercent = $data['changePercentage']['usd'] ?? 0;

        return [
            'rate' => number_format($current['usd'], 2),
            'change' => number_format($changePercent, 2),
            'date' => $current['date'],
            'isPositive' => $changePercent >= 0
        ];
    }
}