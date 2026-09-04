<?php

use App\Models\Screening;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/trainee-verification/{screening}', function (Screening $screening) {
    $screening->loadMissing('batch');

    return view('trainee-verification', compact('screening'));
})->middleware('signed')->name('trainee.verification');
