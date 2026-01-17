<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Alumnus;
use Barryvdh\DomPDF\Facade\Pdf;

class AlumniController extends Controller
{
    public function downloadPdf(Alumnus $alumnus)
    {
        $alumnus->load(['educations','employments','communityInvolvements','engagementPreference','consent']);

        $pdf = Pdf::loadView('exports.alumni_record_pdf', compact('alumnus'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('alumni_record_'.$alumnus->id.'.pdf');
    }
}
