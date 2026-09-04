<?php

namespace App\Filament\Resources\Screenings;

use App\Enums\UserRole;
use App\Filament\Resources\Screenings\Pages\CreateScreening;
use App\Filament\Resources\Screenings\Pages\EditScreening;
use App\Filament\Resources\Screenings\Pages\ListScreenings;
use App\Filament\Resources\Screenings\Schemas\ScreeningForm;
use App\Filament\Resources\Screenings\Schemas\ScreeningInfolist;
use App\Filament\Resources\Screenings\Tables\ScreeningsTable;
use App\Models\Screening;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class ScreeningResource extends Resource
{
    public static function canViewAny(): bool
    {
        // use App\Enums\UserRole;
        // use Illuminate\Support\Facades\Auth;
        $user = Auth::user();

        return $user !== null
            && method_exists($user, 'hasRole')
            && (call_user_func([$user, 'hasRole'], UserRole::Administrator) || call_user_func([$user, 'hasRole'], UserRole::SuperAdmin) || call_user_func([$user, 'hasRole'], UserRole::Trainer));
    }

    protected static ?string $model = Screening::class;

    protected static ?string $navigationLabel = 'Trainees';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function getNavigationSort(): ?int
    {
        return 3; // Lower numbers appear first
    }

    public static function form(Schema $schema): Schema
    {
        return ScreeningForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ScreeningInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ScreeningsTable::configure($table);
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
            'index' => ListScreenings::route(''),
            'create' => CreateScreening::route('create'),
            'edit' => EditScreening::route('{record}/edit'),
        ];
    }
}
