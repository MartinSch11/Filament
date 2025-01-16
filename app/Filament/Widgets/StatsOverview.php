<?php

namespace App\Filament\Widgets;

use App\Models\Holidays;
use App\Models\Timesheet;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalEmployees = User::all()->count();
        $totalHolidays = Holidays::where('type','pending')->count();
        $totalTimesheets = Timesheet::all()->count();

        return [
            Stat::make('Empleados', $totalEmployees),
            Stat::make('Vacaciones solicitadas', $totalHolidays),
            Stat::make('Horarios de trabajo', $totalTimesheets),
        ];
    }
}
