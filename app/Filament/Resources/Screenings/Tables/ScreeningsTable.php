<?php

namespace App\Filament\Resources\Screenings\Tables;

use App\Filament\Exports\ScreeningExporter;
use App\Models\Screening;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;


class ScreeningsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')
                    ->searchable(),
                // TextColumn::make('aptitude_score')
                //     ->numeric()
                //     ->sortable(),
                // TextColumn::make('interview_score')
                //     ->numeric()
                //     ->sortable(),
                TextColumn::make('total_score')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->searchable(),
                // TextColumn::make('phone')
                //     ->searchable(),
                TextColumn::make('batch.batch_name')
                    ->sortable(),
                TextColumn::make('enrolled_status')
                 ->label('Enrollment Status')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn (?Bool $state): string => match ($state) {
                        true => 'Enrolled',
                        false => 'Not Enrolled',
                      
                    })
                    ->badge()
                    ->color(fn (?Bool $state): string => match ($state) {
                        true => 'success',
                        false => 'danger',
                        default => 'success',
                    }),

                  
                // TextColumn::make('address')
                //     ->searchable(),
                // TextColumn::make('date_screened')
                //     ->date()
                //     ->sortable(),
                // TextColumn::make('remarks')
                //     ->searchable(),
                // TextColumn::make('screened_by')
                //     ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])

            ->headerActions([
                ExportAction::make('export')
                    ->label('Export Screenings')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->exporter(ScreeningExporter::class)
                    ->formats([
                        ExportFormat::Csv,
                ])
            ])

            ->recordActions([
                // ViewAction::make(),
                Action::make('enroll')
                    ->label('Enroll')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Confirm Enrollment')
                    ->modalDescription('Are you sure you want to enroll this trainee?')
                    ->modalSubmitActionLabel('Enroll')
                    ->visible(fn (Screening $record): bool => ! $record->enrolled_status)
                    ->action(function (Screening $record) {
                        $batch = $record->batch;

                        if (! $batch || ! $batch->ntp) {
                            Notification::make()
                                ->danger()
                                ->title('Error')
                                ->body('Batch or NTP information is missing.')
                                ->send();

                            return;
                        }

                        $enrolledCount = Screening::query()
                            ->where('enrolled_status', true)
                            ->whereHas('batch', function ($query) use ($batch) {
                                $query->where('ntp_id', $batch->ntp_id);
                            })
                            ->count();

                        if ($enrolledCount >= (int) $batch->ntp->approve_slots) {
                            Notification::make()
                                ->warning()
                                ->title('Batch Capacity Reached')
                                ->body("This NTP has reached its approved slot limit ({$batch->ntp->approve_slots}).")
                                ->send();

                            return;
                        }

                        $record->update(['enrolled_status' => true]);

                        Notification::make()
                            ->success()
                            ->title('Success')
                            ->body('Trainee enrolled successfully.')
                            ->send();
                    }),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
