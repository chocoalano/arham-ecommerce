<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RajaOngkirService
{
    protected ?string $apiKey = null;

    protected string $baseUrl;

    protected string $accountType;

    protected bool $isConfigured = false;

    public function __construct()
    {
        $apiKey = config('services.rajaongkir.key_cost');

        if (empty($apiKey)) {
            Log::warning('RajaOngkir API key is not configured');
            $this->isConfigured = false;
            $this->accountType = 'starter';
            $this->baseUrl = 'https://rajaongkir.komerce.id/api/v1';

            return;
        }

        $this->apiKey = $apiKey;
        $this->accountType = config('services.rajaongkir.type', 'starter');
        $this->baseUrl = $this->getBaseUrl();
        $this->isConfigured = true;
    }

    /**
     * Get base URL based on account type
     */
    protected function getBaseUrl(): string
    {
        // RajaOngkir menggunakan base URL yang sama untuk semua tipe akun
        return 'https://rajaongkir.komerce.id/api/v1';
    }

    /**
     * Make HTTP request to RajaOngkir API
     */
    protected function makeRequest(string $endpoint, array $data = [], string $method = 'GET'): array
    {
        try {
            $url = $this->baseUrl.$endpoint;

            $request = Http::withHeaders([
                'key' => $this->apiKey,
                'Accept' => 'application/json',
            ]);

            $response = $method === 'POST'
                ? $request->asForm()->post($url, $data)
                : $request->get($url, $data);

            if ($response->failed()) {
                Log::error('RajaOngkir API Error', [
                    'endpoint' => $endpoint,
                    'url' => $url,
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);

                return [
                    'success' => false,
                    'message' => 'Gagal terhubung ke RajaOngkir API: '.$response->status(),
                    'data' => null,
                ];
            }

            $result = $response->json();

            // RajaOngkir API response structure: { meta: {...}, data: [...] }
            return [
                'success' => isset($result['meta']) && $result['meta']['status'] === 'success',
                'message' => $result['meta']['message'] ?? 'Success',
                'data' => $result['data'] ?? [],
            ];

        } catch (\Exception $e) {
            Log::error('RajaOngkir Exception', [
                'endpoint' => $endpoint,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ];
        }
    }

    /**
     * Get all provinces
     * Cache for 24 hours since provinces rarely change
     */
    public function getProvinces(): array
    {
        if (! $this->isConfigured) {
            return [
                'success' => false,
                'message' => 'RajaOngkir API key belum dikonfigurasi. Silakan set RAJAONGKIR_API_KEY_COST di file .env',
                'data' => [],
            ];
        }

        return Cache::remember('rajaongkir_provinces', 86400, function () {
            $response = $this->makeRequest('/destination/province');

            if (! $response['success']) {
                return $response;
            }

            return [
                'success' => true,
                'message' => 'Success',
                'data' => $response['data'] ?? [],
            ];
        });
    }

    /**
     * Get province by ID
     */
    public function getProvince(int $provinceId): array
    {
        if (! $this->isConfigured) {
            return [
                'success' => false,
                'message' => 'RajaOngkir API key belum dikonfigurasi',
                'data' => null,
            ];
        }

        return Cache::remember("rajaongkir_province_{$provinceId}", 86400, function () use ($provinceId) {
            $response = $this->makeRequest("/destination/province/{$provinceId}");

            if (! $response['success']) {
                return $response;
            }

            return [
                'success' => true,
                'message' => 'Success',
                'data' => $response['data'] ?? null,
            ];
        });
    }

    /**
     * Get all cities or cities by province
     */
    public function getCities(?int $provinceId = null): array
    {
        if (! $this->isConfigured) {
            return [
                'success' => false,
                'message' => 'RajaOngkir API key belum dikonfigurasi',
                'data' => [],
            ];
        }

        $cacheKey = $provinceId
            ? "rajaongkir_cities_province_{$provinceId}"
            : 'rajaongkir_cities_all';

        return Cache::remember($cacheKey, 86400, function () use ($provinceId) {
            $endpoint = $provinceId
                ? "/destination/city/{$provinceId}"
                : '/destination/city';

            $response = $this->makeRequest($endpoint);

            if (! $response['success']) {
                return $response;
            }

            return [
                'success' => true,
                'message' => 'Success',
                'data' => $response['data'] ?? [],
            ];
        });
    }

    /**
     * Get city by ID
     */
    public function getCity(int $cityId): array
    {
        if (! $this->isConfigured) {
            return [
                'success' => false,
                'message' => 'RajaOngkir API key belum dikonfigurasi',
                'data' => null,
            ];
        }

        return Cache::remember("rajaongkir_city_{$cityId}", 86400, function () use ($cityId) {
            $response = $this->makeRequest("/destination/city/{$cityId}");

            if (! $response['success']) {
                return $response;
            }

            return [
                'success' => true,
                'message' => 'Success',
                'data' => $response['data'] ?? null,
            ];
        });
    }

    /**
     * Get districts/subdistricts by city
     */
    public function getSubdistricts(int $cityId): array
    {
        if (! $this->isConfigured) {
            return [
                'success' => false,
                'message' => 'RajaOngkir API key belum dikonfigurasi',
                'data' => [],
            ];
        }

        return Cache::remember("rajaongkir_districts_{$cityId}", 86400, function () use ($cityId) {
            $response = $this->makeRequest("/destination/district/{$cityId}");

            if (! $response['success']) {
                return $response;
            }

            return [
                'success' => true,
                'message' => 'Success',
                'data' => $response['data'] ?? [],
            ];
        });
    }

    /**
     * Calculate shipping cost - Domestic
     *
     * @param  int  $origin  Origin district/city ID
     * @param  int  $destination  Destination district/city ID
     * @param  int  $weight  Weight in grams
     * @param  string  $courier  Courier code (jne, pos, tiki, etc)
     */
    public function getCost(int $origin, int $destination, int $weight, string $courier): array
    {
        if (! $this->isConfigured) {
            return [
                'success' => false,
                'message' => 'RajaOngkir API key belum dikonfigurasi',
                'data' => [],
            ];
        }

        $data = [
            'origin' => $origin,
            'destination' => $destination,
            'weight' => $weight,
            'courier' => strtolower($courier),
        ];

        $response = $this->makeRequest('/calculate/domestic-cost', $data, 'POST');

        if (! $response['success']) {
            return $response;
        }

        // Parse response - API returns array of services directly in data
        // Format: { data: [{ name, code, service, description, cost, etd }] }
        $costs = [];

        if (! empty($response['data']) && is_array($response['data'])) {
            $courierCode = strtoupper($courier);
            $courierName = '';

            foreach ($response['data'] as $service) {
                if (empty($courierName)) {
                    $courierName = $service['name'] ?? $courierCode;
                }

                $costs[] = [
                    'service' => $service['service'] ?? '',
                    'description' => $service['description'] ?? '',
                    'cost' => [
                        [
                            'value' => (int) ($service['cost'] ?? 0),
                            'etd' => $service['etd'] ?? '',
                            'note' => '',
                        ],
                    ],
                ];
            }

            return [
                'success' => true,
                'message' => 'Success',
                'data' => [
                    [
                        'code' => $courierCode,
                        'name' => $courierName,
                        'costs' => $costs,
                    ],
                ],
            ];
        }

        return [
            'success' => true,
            'message' => 'Success',
            'data' => [],
        ];
    }

    /**
     * Calculate shipping cost for multiple couriers
     */
    public function getMultipleCosts(int $origin, int $destination, int $weight, array $couriers = ['jne', 'pos', 'tiki']): array
    {
        if (! $this->isConfigured) {
            return [
                'success' => false,
                'message' => 'RajaOngkir API key belum dikonfigurasi',
                'data' => [],
            ];
        }

        $results = [];

        foreach ($couriers as $courier) {
            if (! $this->isCourierSupported($courier)) {
                continue;
            }

            $response = $this->getCost($origin, $destination, $weight, $courier);

            if ($response['success'] && ! empty($response['data'])) {
                $results = array_merge($results, $response['data']);
            }
        }

        return [
            'success' => true,
            'message' => 'Success',
            'data' => $results,
        ];
    }

    /**
     * Get supported couriers based on account type
     */
    public function getSupportedCouriers(): array
    {
        $allCouriers = [
            'starter' => ['jne', 'pos', 'tiki'],
            'basic' => ['jne', 'pos', 'tiki', 'pcp', 'esl', 'rpx'],
            'pro' => [
                'jne', 'pos', 'tiki', 'pcp', 'esl', 'rpx',
                'pandu', 'wahana', 'sicepat', 'jnt', 'pahala',
                'sap', 'jet', 'indah', 'dse', 'slis', 'first',
                'ncs', 'star', 'ninja', 'lion', 'idl', 'rex',
                'ide', 'sentral', 'anteraja',
            ],
        ];

        return $allCouriers[$this->accountType] ?? $allCouriers['starter'];
    }

    /**
     * Check if courier is supported
     */
    public function isCourierSupported(string $courier): bool
    {
        $supported = $this->getSupportedCouriers();

        return in_array(strtolower($courier), $supported);
    }

    /**
     * Clear all cached RajaOngkir data
     */
    public function clearCache(): void
    {
        Cache::forget('rajaongkir_provinces');
        Cache::forget('rajaongkir_cities_all');
        // Note: Individual province/city caches will expire naturally after 24 hours
    }
}
