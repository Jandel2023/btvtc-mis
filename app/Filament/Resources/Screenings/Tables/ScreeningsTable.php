<?php

namespace App\Filament\Resources\Screenings\Tables;

use App\Filament\Exports\ScreeningExporter;
use App\Models\Screening;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;

class ScreeningsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                // FULL NAME
                TextColumn::make('full_name')
                    ->label('Full Name')
                    ->getStateUsing(
                        fn (Screening $record): string => $record->full_name
                    )
                    ->searchable(query: function ($query, string $search): void {
                        $query->where(function ($query) use ($search) {
                            $query
                                ->where('fname', 'like', "%{$search}%")
                                ->orWhere('mname', 'like', "%{$search}%")
                                ->orWhere('lname', 'like', "%{$search}%");
                        });
                    })
                    ->sortable(false),


                // BATCH
                TextColumn::make('batch.batch_name')
                    ->label('Batch')
                    ->sortable()
                    ->searchable(),
                // TOTAL SCORE
                TextColumn::make('total_score')
                    ->numeric()
                    ->sortable(),

                // STATUS
                TextColumn::make('status')
                    ->searchable(),

                // ENROLLMENT STATUS
               TextColumn::make('enrolled_status')
                    ->label('Enrollment Status')
                    ->formatStateUsing(fn (?bool $state): string => match ($state) {
                        true => 'Enrolled',
                        false => 'Not Enrolled',
                        default => 'Not Enrolled',
                    })
                    ->badge()
                    ->color(fn (?bool $state): string => match ($state) {
                        true => 'success',
                        false => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),

                // CREATED AT
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // UPDATED AT
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

         ->filters([
                SelectFilter::make('enrolled_status')
                    ->label('Enrollment Status')
                    ->options([
                        1 => 'Enrolled',
                        0 => 'Not Enrolled',
                    ]),
            ])

            // EXPORT
            ->headerActions([
                ExportAction::make('export')
                    ->label('Export Screenings')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->exporter(ScreeningExporter::class)
                    ->formats([
                        ExportFormat::Csv,
                    ]),
            ])

            // RECORD ACTIONS
            ->recordActions([

                // ENROLL
                Action::make('enroll')
                    ->label('Enroll')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Confirm Enrollment')
                    ->modalDescription(
                        'Are you sure you want to enroll this trainee?'
                    )
                    ->modalSubmitActionLabel('Enroll')

                  ->visible(
                    fn (Screening $record): bool =>
                        $record->status === 'Passed'
                        && ! $record->enrolled_status
                        )

                    ->action(function (Screening $record): void {

                        $batch = $record->batch;

                        // CHECK BATCH AND NTP
                        if (! $batch || ! $batch->ntp) {

                            Notification::make()
                                ->danger()
                                ->title('Error')
                                ->body(
                                    'Batch or NTP information is missing.'
                                )
                                ->send();

                            return;
                        }

                        // COUNT ENROLLED TRAINEES
                        $enrolledCount = Screening::query()
                            ->where('enrolled_status', true)
                            ->whereHas('batch', function ($query) use ($batch) {
                                $query->where(
                                    'ntp_id',
                                    $batch->ntp_id
                                );
                            })
                            ->count();

                        // CHECK APPROVED SLOTS
                        if (
                            $enrolledCount >=
                            (int) $batch->ntp->approve_slots
                        ) {

                            Notification::make()
                                ->warning()
                                ->title('Batch Capacity Reached')
                                ->body(
                                    "This NTP has reached its approved slot limit ({$batch->ntp->approve_slots})."
                                )
                                ->send();

                            return;
                        }

                        // ENROLL
                        $record->update([
                            'enrolled_status' => true,
                        ]);

                        // SUCCESS MESSAGE
                        Notification::make()
                            ->success()
                            ->title('Success')
                            ->body(
                                'Trainee enrolled successfully.'
                            )
                            ->send();
                    }),

                // EDIT
                EditAction::make(),
            ])

            // BULK ACTIONS
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
