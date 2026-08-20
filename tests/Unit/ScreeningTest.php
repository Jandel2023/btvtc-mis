<?php

use App\Models\Screening;

test('it combines a screening applicant name into a full name', function () {
    $screening = new Screening([
        'fname' => 'Maria',
        'mname' => 'Reyes',
        'lname' => 'Santos',
    ]);

    expect($screening->full_name)->toBe('Maria Reyes Santos');
});

test('it omits a missing middle name from the full name', function () {
    $screening = new Screening([
        'fname' => 'John',
        'lname' => 'Dela Cruz',
    ]);

    expect($screening->full_name)->toBe('John Dela Cruz');
});
