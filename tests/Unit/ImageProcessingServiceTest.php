<?php

use App\Services\ImageProcessingService;

it('has the required methods', function () {
    $service = new ImageProcessingService;

    expect(method_exists($service, 'uploadWithRatios'))->toBeTrue();
    expect(method_exists($service, 'deleteAllVersions'))->toBeTrue();
});

it('can instantiate service', function () {
    $service = new ImageProcessingService;

    expect($service)->toBeInstanceOf(ImageProcessingService::class);
});

// Note: Full integration tests for image processing should be done
// in Feature tests with actual file uploads through the Filament form
// or using real image files, not fake() files.
//
// To test manually:
// 1. Go to Filament Admin
// 2. Create/Edit a Product
// 3. Upload an image
// 4. Check storage/app/public/products/ for generated files:
//    - *_original.* (original format)
//    - *_ratio_27_28.webp (540×560)
//    - *_ratio_108_53.webp (540×265)
//    - *_ratio_51_52.webp (255×260)
//    - *_ratio_99_119.webp (198×238)
// 5. Verify database columns are filled:
//    - path_ratio_27_28
//    - path_ratio_108_53
//    - path_ratio_51_52
//    - path_ratio_99_119
