<?php

namespace App\Filament\Dashboard\Resources\PersonalResource\Widgets;

use App\Filament\Dashboard\Resources\HolidayResource\Pages\ListHolidays;
use App\Models\Holidays;
use App\Models\Timesheet;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class PersonalWidgetStats extends BaseWidget
{
    protected function getStats(): array
    {


        return [
            Stat::make('Pending Holidays', $this->getPendingHoliday(Auth::user())),
            Stat::make('Approved Holidays', $this->getApprovedHoliday(Auth::user())),
            Stat::make('Total Work', $this->getTotalWork(Auth::user())),
            Stat::make('Total Pause', $this->getTotalPause(Auth::user())),
        ];
    }

    protected function getPendingHoliday(User $user)
    {
        $totalPendingHolidays = Holidays::where('user_id', $user->id)
            ->where('type', 'pending')->get()->count();

        return $totalPendingHolidays;
    }
    protected function getApprovedHoliday(User $user)
    {
        $totalApprovedHolidays = Holidays::where('user_id', $user->id)
            ->where('type', 'approved')->get()->count();

        return $totalApprovedHolidays;
    }
    protected function getTotalWork(User $user)
    {
        // Obtén los registros de hoy para el usuario y el tipo 'work'
        $timesheets = Timesheet::where('user_id', $user->id)
            ->where('type', 'work')
            ->whereDate('created_at', Carbon::today())
            ->get();

        $sumSeconds = 0;

        foreach ($timesheets as $timesheet) {
            // Si el usuario ha registrado un `day_in` y un `day_out`
            if ($timesheet->day_in && $timesheet->day_out) {
                $startTime = Carbon::parse($timesheet->day_in);
                $finishTime = Carbon::parse($timesheet->day_out);

                // Verifica que `day_out` sea posterior a `day_in`
                if ($startTime->lte($finishTime)) {
                    $totalDuration = $finishTime->diffInSeconds($startTime);
                    $sumSeconds += $totalDuration;
                }
            }
            // Si el usuario tiene un `day_in` pero no un `day_out`, está trabajando actualmente
            elseif ($timesheet->day_in && !$timesheet->day_out) {
                $startTime = Carbon::parse($timesheet->day_in);

                // Verifica que `day_in` no sea una fecha futura
                if ($startTime->lte(now())) {
                    $currentDuration = now()->diffInSeconds($startTime);
                    $sumSeconds += $currentDuration;
                }
            }
        }

        $hours = floor($sumSeconds / 3600);
        $minutes = floor(($sumSeconds % 3600) / 60);
        $seconds = $sumSeconds % 60;

        return sprintf('%02d:%02d:%02d', abs($hours), abs($minutes), abs($seconds));
    }

    protected function getTotalPause(User $user)
    {
        $timesheets = Timesheet::where('user_id', $user->id)
            ->where('type', 'pause')
            ->whereDate('created_at', Carbon::today())
            ->get();

        $sumSeconds = 0;

        foreach ($timesheets as $timesheet) {
            if ($timesheet->day_in && $timesheet->day_out) {
                $startTime = Carbon::parse($timesheet->day_in);
                $finishTime = Carbon::parse($timesheet->day_out);

                if ($startTime->lte($finishTime)) {
                    $totalDuration = $finishTime->diffInSeconds($startTime);
                    $sumSeconds += $totalDuration;
                }
            }
            elseif ($timesheet->day_in && !$timesheet->day_out) {
                $startTime = Carbon::parse($timesheet->day_in);

                if ($startTime->lte(now())) {
                    $currentDuration = now()->diffInSeconds($startTime);
                    $sumSeconds += $currentDuration;
                }
            }
        }

        $hours = floor($sumSeconds / 3600);
        $minutes = floor(($sumSeconds % 3600) / 60);
        $seconds = $sumSeconds % 60;

        return sprintf('%02d:%02d:%02d', abs($hours), abs($minutes), abs($seconds));
    }
}
