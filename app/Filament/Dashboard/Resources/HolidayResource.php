<?php

namespace App\Filament\Dashboard\Resources;

use App\Filament\Dashboard\Resources\HolidayResource\Pages;
use App\Filament\Dashboard\Resources\HolidayResource\RelationManagers;
use App\Models\Holidays;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class HolidayResource extends Resource
{
    protected static ?string $model = Holidays::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', Auth::user()->id, true);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('calendar_id')
                    ->relationship(name: 'calendar', titleAttribute: 'name')
                    ->Label('Calendario')
                    ->required(),

                Forms\Components\DatePicker::make('day')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('calendar.name')
                    ->searchable()
                    ->sortable()
                    ->Label('Calendario'),
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('day')
                    ->searchable()
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'gray',
                        'decline' => 'danger',
                        'approved' => 'success',
                    })
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'pending' => 'Pending',
                        'decline' => 'Decline',
                        'approved' => 'Approved',
                    ])
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHolidays::route('/'),
            'create' => Pages\CreateHoliday::route('/create'),
            'edit' => Pages\EditHoliday::route('/{record}/edit'),
        ];
    }
}
