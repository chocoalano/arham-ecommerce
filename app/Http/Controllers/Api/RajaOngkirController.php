<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\RajaOngkirService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RajaOngkirController extends Controller
{
    public function __construct(
        protected RajaOngkirService $rajaOngkir
    ) {}

    /**
     * Get all provinces
     */
    public function provinces(): JsonResponse
    {
        try {
            $result = $this->rajaOngkir->getProvinces();

            return response()->json($result);
        } catch (\RuntimeException $e) {
            // If API key not configured, return mock data for development
            if (str_contains($e->getMessage(), 'not configured')) {
                return response()->json([
                    'success' => false,
                    'message' => 'RajaOngkir API key belum dikonfigurasi. Silakan set RAJAONGKIR_API_KEY_COST di .env',
                    'data' => [],
                ]);
            }
            throw $e;
        }
    }

    /**
     * Get cities by province
     */
    public function cities(Request $request): JsonResponse
    {
        $request->validate([
            'province_id' => ['nullable', 'integer'],
        ]);

        $provinceId = $request->input('province_id');
        $result = $this->rajaOngkir->getCities($provinceId);

        return response()->json($result);
    }

    /**
     * Get subdistricts by city (Pro account only)
     */
    public function subdistricts(Request $request): JsonResponse
    {
        $request->validate([
            'city_id' => ['required', 'integer'],
        ]);

        $cityId = $request->input('city_id');
        $result = $this->rajaOngkir->getSubdistricts($cityId);

        return response()->json($result);
    }

    /**
     * Calculate shipping cost for single courier
     */
    public function calculateCost(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'origin' => ['required', 'integer'],
            'destination' => ['required', 'integer'],
            'weight' => ['required', 'integer', 'min:1'],
            'courier' => ['required', 'string'],
        ]);

        $result = $this->rajaOngkir->getCost(
            $validated['origin'],
            $validated['destination'],
            $validated['weight'],
            $validated['courier']
        );

        return response()->json($result);
    }

    /**
     * Calculate shipping cost for multiple couriers
     */
    public function calculateMultipleCosts(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'origin' => ['required', 'integer'],
            'destination' => ['required', 'integer'],
            'weight' => ['required', 'integer', 'min:1'],
            'couriers' => ['required', 'array'],
            'couriers.*' => ['required', 'string'],
        ]);

        $result = $this->rajaOngkir->getMultipleCosts(
            $validated['origin'],
            $validated['destination'],
            $validated['weight'],
            $validated['couriers']
        );

        return response()->json($result);
    }

    /**
     * Get list of supported couriers
     */
    public function couriers(): JsonResponse
    {
        $couriers = $this->rajaOngkir->getSupportedCouriers();

        return response()->json([
            'success' => true,
            'data' => $couriers,
        ]);
    }
}
