<?php

namespace App\Filament\Dashboard\Resources\HolidayResource\Pages;

use App\Filament\Dashboard\Resources\HolidayResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateHoliday extends CreateRecord
{
    protected static string $resource = HolidayResource::class;
    protected function getRedirectUrl(): string
    {
        // Redirigir al índice de la lista de recursos después de crear un registro
        return static::getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
{
    $data['user_id'] = Auth::user()->id;
    $data['type'] = 'pending';

    return $data;
}
}
