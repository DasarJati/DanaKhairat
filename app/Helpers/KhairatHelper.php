<?php

use Carbon\Carbon;

function extractDobAgeFromIc($ic)
{
    if (strlen($ic) < 6) {
        return [
            'tarikh_lahir' => null,
            'umur' => null
        ];
    }

    $yy = substr($ic, 0, 2);
    $mm = substr($ic, 2, 2);
    $dd = substr($ic, 4, 2);

    // Determine century
    $yearPrefix = $yy > date('y') ? '19' : '20';
    $fullYear = $yearPrefix . $yy;

    $dob = Carbon::createFromDate($fullYear, $mm, $dd);
    $age = $dob->age;

    return [
        'tarikh_lahir' => $dob->format('Y-m-d'),
        'umur' => $age
    ];
}
