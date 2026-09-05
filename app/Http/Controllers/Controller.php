<?php

namespace App\Http\Controllers;

use App\Models\Screening;
use Barryvdh\DomPDF\Facade\Pdf;

abstract class Controller
{
    //
   
    public function pdf(Screening $screening)
    {
        
         $pdf = Pdf::loadView('reports.id-reports', ['data' => $screening])->setPaper('letter', 'portrait');
        return $pdf->stream('id-template-' . $screening->id . '.pdf');

    }
}

