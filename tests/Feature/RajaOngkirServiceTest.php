<?php

use App\Services\RajaOngkirService;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    config(['services.rajaongkir.key' => 'test-api-key']);
    config(['services.rajaongkir.type' => 'starter']);
});

it('can get provinces', function () {
    Http::fake([
        'https://api.rajaongkir.com/starter/province' => Http::response([
            'rajaongkir' => [
                'status' => ['code' => 200, 'description' => 'OK'],
                'results' => [
                    ['province_id' => '1', 'province' => 'Bali'],
                    ['province_id' => '2', 'province' => 'Bangka Belitung'],
                ],
            ],
        ], 200),
    ]);

    $service = new RajaOngkirService;
    $result = $service->getProvinces();

    expect($result['success'])->toBeTrue();
    expect($result['data'])->toBeArray();
    expect($result['data'])->toHaveCount(2);
});

it('can get cities by province', function () {
    Http::fake([
        'https://api.rajaongkir.com/starter/city?province=9' => Http::response([
            'rajaongkir' => [
                'status' => ['code' => 200, 'description' => 'OK'],
                'results' => [
                    ['city_id' => '153', 'province_id' => '9', 'city_name' => 'Jakarta Pusat'],
                    ['city_id' => '154', 'province_id' => '9', 'city_name' => 'Jakarta Selatan'],
                ],
            ],
        ], 200),
    ]);

    $service = new RajaOngkirService;
    $result = $service->getCities(9);

    expect($result['success'])->toBeTrue();
    expect($result['data'])->toBeArray();
    expect($result['data'])->toHaveCount(2);
});

it('can calculate shipping cost', function () {
    Http::fake([
        'https://api.rajaongkir.com/starter/cost' => Http::response([
            'rajaongkir' => [
                'status' => ['code' => 200, 'description' => 'OK'],
                'results' => [
                    [
                        'code' => 'jne',
                        'name' => 'Jalur Nugraha Ekakurir (JNE)',
                        'costs' => [
                            [
                                'service' => 'REG',
                                'description' => 'Layanan Reguler',
                                'cost' => [
                                    ['value' => 25000, 'etd' => '1-2', 'note' => ''],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ], 200),
    ]);

    $service = new RajaOngkirService;
    $result = $service->getCost(153, 501, 1000, 'jne');

    expect($result['success'])->toBeTrue();
    expect($result['data'])->toBeArray();
    expect($result['data'][0]['code'])->toBe('jne');
});

it('returns list of supported couriers', function () {
    $service = new RajaOngkirService;
    $couriers = $service->getCouriers();

    expect($couriers)->toBeArray();
    expect($couriers)->not->toBeEmpty();
    expect($couriers[0])->toHaveKeys(['code', 'name']);
});

it('can check if courier is supported', function () {
    $service = new RajaOngkirService;

    expect($service->isCourierSupported('jne'))->toBeTrue();
    expect($service->isCourierSupported('pos'))->toBeTrue();
    expect($service->isCourierSupported('invalid'))->toBeFalse();
});

it('handles API errors gracefully', function () {
    Http::fake([
        'https://api.rajaongkir.com/starter/province' => Http::response([], 500),
    ]);

    $service = new RajaOngkirService;
    $result = $service->getProvinces();

    expect($result['success'])->toBeFalse();
    expect($result['message'])->toBeString();
});
