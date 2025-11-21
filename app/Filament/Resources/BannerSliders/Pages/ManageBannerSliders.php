<?php

namespace App\Filament\Resources\BannerSliders\Pages;

use App\Filament\Resources\BannerSliders\BannerSliderResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageBannerSliders extends ManageRecords
{
    protected static string $resource = BannerSliderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
