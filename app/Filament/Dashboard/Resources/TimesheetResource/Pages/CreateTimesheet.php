<?php

namespace App\Filament\Dashboard\Resources\TimesheetResource\Pages;

use App\Filament\Dashboard\Resources\TimesheetResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateTimesheet extends CreateRecord
{
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::user()->id;

        return $data;
    }
    protected static string $resource = TimesheetResource::class;
}


