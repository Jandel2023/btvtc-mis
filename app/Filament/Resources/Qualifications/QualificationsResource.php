<?php

namespace App\Filament\Resources\Qualifications;

use App\Enums\UserRole;
use App\Filament\Resources\Qualifications\Pages\CreateQualifications;
use App\Filament\Resources\Qualifications\Pages\EditQualifications;
use App\Filament\Resources\Qualifications\Pages\ListQualifications;
use App\Filament\Resources\Qualifications\Pages\ViewQualifications;
use App\Filament\Resources\Qualifications\Schemas\QualificationsForm;
use App\Filament\Resources\Qualifications\Schemas\QualificationsInfolist;
use App\Filament\Resources\Qualifications\Tables\QualificationsTable;
use App\Models\Qualifications;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class QualificationsResource extends Resource
{
    protected static ?string $model = Qualifications::class;

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

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-clipboard-document-check';
    }

    public static function getNavigationSort(): ?int
    {
        return 5; // Lower numbers appear first
    }

    public static function form(Schema $schema): Schema
    {
        return QualificationsForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return QualificationsInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return QualificationsTable::configure($table);
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
            'index' => ListQualifications::route('/'),
            'create' => CreateQualifications::route('/create'),
            'view' => ViewQualifications::route('/{record}'),
            'edit' => EditQualifications::route('/{record}/edit'),
        ];
    }
}
