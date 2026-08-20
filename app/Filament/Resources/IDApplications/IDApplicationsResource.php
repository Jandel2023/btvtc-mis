<?php

namespace App\Filament\Resources\IDApplications;

use App\Filament\Resources\IDApplications\Pages\CreateIDApplications;
use App\Filament\Resources\IDApplications\Pages\EditIDApplications;
use App\Filament\Resources\IDApplications\Pages\ListIDApplications;
use App\Filament\Resources\IDApplications\Pages\ViewIDApplications;
use App\Filament\Resources\IDApplications\Schemas\IDApplicationsForm;
use App\Filament\Resources\IDApplications\Schemas\IDApplicationsInfolist;
use App\Filament\Resources\IDApplications\Tables\IDApplicationsTable;
use App\Models\IDApplications;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class IDApplicationsResource extends Resource
{
    protected static ?string $model = IDApplications::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return IDApplicationsForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return IDApplicationsInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return IDApplicationsTable::configure($table);
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
            'index' => ListIDApplications::route('/'),
            'create' => CreateIDApplications::route('/create'),
            'view' => ViewIDApplications::route('/{record}'),
            'edit' => EditIDApplications::route('/{record}/edit'),
        ];
    }
}
