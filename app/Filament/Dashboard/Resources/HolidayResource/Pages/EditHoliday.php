<?php

namespace App\Filament\Dashboard\Resources\HolidayResource\Pages;

use App\Filament\Dashboard\Resources\HolidayResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHoliday extends EditRecord
{
    protected static string $resource = HolidayResource::class;

    protected function getRedirectUrl(): string
    {
        // Redirigir al índice de la lista de recursos después de crear un registro
        return static::getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
