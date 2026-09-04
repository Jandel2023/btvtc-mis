<?php

namespace App\Filament\Resources\ReleaseToolkits;

use App\Enums\UserRole;
use App\Filament\Resources\ReleaseToolkits\Pages\CreateReleaseToolkit;
use App\Filament\Resources\ReleaseToolkits\Pages\EditReleaseToolkit;
use App\Filament\Resources\ReleaseToolkits\Pages\ListReleaseToolkits;
use App\Filament\Resources\ReleaseToolkits\Pages\ViewReleaseToolkit;
use App\Filament\Resources\ReleaseToolkits\Schemas\ReleaseToolkitForm;
use App\Filament\Resources\ReleaseToolkits\Schemas\ReleaseToolkitInfolist;
use App\Filament\Resources\ReleaseToolkits\Tables\ReleaseToolkitsTable;
use App\Models\ReleaseToolkit;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class ReleaseToolkitResource extends Resource
{
    protected static ?string $model = ReleaseToolkit::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    //       public static function getNavigationIcon(): string
    // {
    //     return ' heroicon-o-inbox-stack';
    // }
    public static function getNavigationSort(): ?int
    {
        return 4; // Lower numbers appear first
    }

    public static function form(Schema $schema): Schema
    {
        return ReleaseToolkitForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ReleaseToolkitInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReleaseToolkitsTable::configure($table);
    }

    public static function canViewAny(): bool
    {
        $user = Auth::user();

        return $user !== null
            && method_exists($user, 'hasRole')
            && (call_user_func([$user, 'hasRole'], UserRole::Administrator)
                || call_user_func([$user, 'hasRole'], UserRole::Trainer) || call_user_func([$user, 'hasRole'], UserRole::SuperAdmin));
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
            'index' => ListReleaseToolkits::route('/'),
            'create' => CreateReleaseToolkit::route('/create'),
            'view' => ViewReleaseToolkit::route('/{record}'),
            'edit' => EditReleaseToolkit::route('/{record}/edit'),
        ];
    }
}
