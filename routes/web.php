<?php

use App\Filament\Resources\Screenings\Tables\ScreeningsTable;
use App\Models\Screening;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/trainee-verification/{screening}', function (Screening $screening) {
    $screening->loadMissing('batch');

    return view('trainee-verification', compact('screening'));
})->middleware('signed')->name('trainee.verification');

Route::get('/screenings/{screening}/id-card-pdf', function (Screening $screening) {
    $screening->loadMissing('batch.qualification');

    $verificationUrl = URL::temporarySignedRoute(
        'trainee.verification',
        now()->addYear(),
        ['screening' => $screening],
    );

    $pdf = Pdf::loadView('filament.id-card', [
        'record' => $screening,
        'qrCode' => ScreeningsTable::qrCodeMarkup($verificationUrl),
    ])->setPaper('a4','landscape');

    return $pdf->stream("trainee-id-{$screening->id}.pdf");
})->name('screenings.id-card.pdf');

