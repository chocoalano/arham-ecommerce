<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageProcessingService
{
    /**
     * Upload dan generate 4 versi gambar dengan ratio berbeda
     *
     * @return array{original: string, ratio_27_28: string, ratio_108_53: string, ratio_51_52: string, ratio_99_119: string}
     */
    public function uploadWithRatios(UploadedFile $file, string $directory = 'products', string $disk = 'public'): array
    {
        $filename = time().'_'.uniqid();
        $basePath = $directory.'/'.$filename;

        $paths = [];

        // Load original image
        $sourceImage = $this->createImageFromFile($file->getPathname());
        if (! $sourceImage) {
            throw new \RuntimeException('Failed to load image');
        }

        $originalWidth = imagesx($sourceImage);
        $originalHeight = imagesy($sourceImage);

        // 1. Original (simpan tanpa resize untuk kualitas maksimal)
        $originalPath = $basePath.'_original.'.$file->getClientOriginalExtension();
        Storage::disk($disk)->put($originalPath, file_get_contents($file->getPathname()));
        $paths['original'] = $originalPath;

        // 2. Ratio 27:28 - 540x560
        $ratio27_28Image = $this->resizeImageCover($sourceImage, $originalWidth, $originalHeight, 540, 560);
        $ratio27_28Path = $basePath.'_ratio_27_28.webp';
        Storage::disk($disk)->put($ratio27_28Path, $this->imageToWebP($ratio27_28Image, 85));
        imagedestroy($ratio27_28Image);
        $paths['ratio_27_28'] = $ratio27_28Path;

        // 3. Ratio 108:53 - 540x265
        $ratio108_53Image = $this->resizeImageCover($sourceImage, $originalWidth, $originalHeight, 540, 265);
        $ratio108_53Path = $basePath.'_ratio_108_53.webp';
        Storage::disk($disk)->put($ratio108_53Path, $this->imageToWebP($ratio108_53Image, 85));
        imagedestroy($ratio108_53Image);
        $paths['ratio_108_53'] = $ratio108_53Path;

        // 4. Ratio 51:52 - 255x260
        $ratio51_52Image = $this->resizeImageCover($sourceImage, $originalWidth, $originalHeight, 255, 260);
        $ratio51_52Path = $basePath.'_ratio_51_52.webp';
        Storage::disk($disk)->put($ratio51_52Path, $this->imageToWebP($ratio51_52Image, 85));
        imagedestroy($ratio51_52Image);
        $paths['ratio_51_52'] = $ratio51_52Path;

        // 5. Ratio 99:119 - 198x238
        $ratio99_119Image = $this->resizeImageCover($sourceImage, $originalWidth, $originalHeight, 198, 238);
        $ratio99_119Path = $basePath.'_ratio_99_119.webp';
        Storage::disk($disk)->put($ratio99_119Path, $this->imageToWebP($ratio99_119Image, 85));
        imagedestroy($ratio99_119Image);
        $paths['ratio_99_119'] = $ratio99_119Path;

        imagedestroy($sourceImage);

        return $paths;
    }

    /**
     * Create GD image resource from file
     *
     * @return \GdImage|false
     */
    protected function createImageFromFile(string $path): mixed
    {
        $imageInfo = getimagesize($path);
        if (! $imageInfo) {
            return false;
        }

        return match ($imageInfo[2]) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($path),
            IMAGETYPE_PNG => imagecreatefrompng($path),
            IMAGETYPE_WEBP => imagecreatefromwebp($path),
            default => false,
        };
    }

    /**
     * Resize image dengan cover mode (crop to fit)
     *
     * @param  \GdImage  $sourceImage
     * @return \GdImage
     */
    protected function resizeImageCover($sourceImage, int $originalWidth, int $originalHeight, int $targetWidth, int $targetHeight): mixed
    {
        // Calculate ratios
        $srcRatio = $originalWidth / $originalHeight;
        $targetRatio = $targetWidth / $targetHeight;

        // Determine crop dimensions
        if ($srcRatio > $targetRatio) {
            // Image is wider, crop width
            $newHeight = $originalHeight;
            $newWidth = (int) ($originalHeight * $targetRatio);
            $srcX = (int) (($originalWidth - $newWidth) / 2);
            $srcY = 0;
        } else {
            // Image is taller, crop height
            $newWidth = $originalWidth;
            $newHeight = (int) ($originalWidth / $targetRatio);
            $srcX = 0;
            $srcY = (int) (($originalHeight - $newHeight) / 2);
        }

        // Create destination image
        $destImage = imagecreatetruecolor($targetWidth, $targetHeight);

        // Preserve transparency for PNG/WEBP
        imagealphablending($destImage, false);
        imagesavealpha($destImage, true);

        // Copy and resize
        imagecopyresampled(
            $destImage,
            $sourceImage,
            0, 0,
            $srcX, $srcY,
            $targetWidth, $targetHeight,
            $newWidth, $newHeight
        );

        return $destImage;
    }

    /**
     * Convert GD image to WebP string
     *
     * @param  \GdImage  $image
     */
    protected function imageToWebP($image, int $quality = 85): string
    {
        ob_start();
        imagewebp($image, null, $quality);

        return ob_get_clean();
    }

    /**
     * Hapus semua versi gambar
     */
    public function deleteAllVersions(string $originalPath, string $disk = 'public'): void
    {
        $pathInfo = pathinfo($originalPath);
        $baseName = str_replace('_original', '', $pathInfo['filename']);
        $directory = $pathInfo['dirname'];

        $suffixes = ['_original', '_ratio_27_28', '_ratio_108_53', '_ratio_51_52', '_ratio_99_119'];
        $extensions = ['jpg', 'jpeg', 'png', 'webp'];

        foreach ($suffixes as $suffix) {
            foreach ($extensions as $ext) {
                $path = $directory.'/'.$baseName.$suffix.'.'.$ext;
                if (Storage::disk($disk)->exists($path)) {
                    Storage::disk($disk)->delete($path);
                }
            }
        }
    }
}
