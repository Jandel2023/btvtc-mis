<?php

namespace App\Filament\Resources\Ntps;

use App\Enums\UserRole;
use App\Filament\Resources\Ntps\Pages\CreateNtp;
use App\Filament\Resources\Ntps\Pages\EditNtp;
use App\Filament\Resources\Ntps\Pages\ListNtps;
use App\Filament\Resources\Ntps\Pages\ViewNtp;
use App\Filament\Resources\Ntps\Schemas\NtpForm;
use App\Filament\Resources\Ntps\Schemas\NtpInfolist;
use App\Filament\Resources\Ntps\Tables\NtpsTable;
use App\Models\Ntp;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class NtpResource extends Resource
{
    protected static ?string $model = Ntp::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function canViewAny(): bool
    {
        // use App\Enums\UserRole;
        // use Illuminate\Support\Facades\Auth;
        $user = Auth::user();

        return $user !== null
            && method_exists($user, 'hasRole')
            && (call_user_func([$user, 'hasRole'], UserRole::Administrator) || call_user_func([$user, 'hasRole'], UserRole::SuperAdmin));
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Settings';
    }

    public static function getNavigationSort(): ?int
    {
        return 1; // Lower numbers appear first
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-document'; // Outline user icon
    }

    public static function form(Schema $schema): Schema
    {
        return NtpForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return NtpInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NtpsTable::configure($table);
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
            'index' => ListNtps::route('/'),
            'create' => CreateNtp::route('/create'),
            'view' => ViewNtp::route('/{record}'),
            'edit' => EditNtp::route('/{record}/edit'),
        ];
    }
}
