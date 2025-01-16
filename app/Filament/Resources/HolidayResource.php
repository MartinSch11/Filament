<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HolidayResource\Pages;
use App\Filament\Resources\HolidayResource\RelationManagers;
use App\Models\Holidays;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HolidayResource extends Resource
{
    protected static ?string $model = Holidays::class;
    protected static ?string $navigationGroup = 'Manejo de usuarios';
    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('calendar_id')
                    ->relationship(name: 'calendar', titleAttribute: 'name')
                    ->Label('Calendario')
                    ->required(),

                Forms\Components\Select::make('user_id')
                    ->relationship(name: 'user', titleAttribute: 'name')
                    ->Label('Usuario')
                    ->required(),

                Forms\Components\DatePicker::make('day')
                    ->required(),

                Forms\Components\Select::make('type')
                    ->options([
                        'decline' => 'Decline',
                        'approved' => 'Approved',
                        'pending' => 'Pending',
                    ])
                    ->Label('Accion')
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
                    ->color(fn (string $state): string => match ($state) {
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
