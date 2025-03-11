<?php

namespace App\Http\Controllers;

use App\Models\CertificateRequest;
use App\Models\SupplyActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PDF;

class ReportController extends Controller
{

    public function DownloadCertificate(Request $request){
        $data = [
            'firstParagraph' => $request->query('firstParagraph', ''),
            'secondParagraph' => $request->query('secondParagraph', ''),
            'thirdParagraph' => $request->query('thirdParagraph', '')
        ];
        
        $id = $request->query('id');

        if($id != "null"){
            CertificateRequest::where('id', $id)->update(['date_given' => now()]);
        }
        $pdf = PDF::loadView('Reports.pdf.certificate', compact('data'))->setPaper('letter');
        //return $pdf->download('certificate.pdf');

        return $pdf->download('certificate.pdf');
    }

    public function DownloadIncidentReport(Request $request){
        $data = [
            'informant' => $request->query('informant', ''),
            'incident_type' => $request->query('incident_type', ''),
            'rationale' => $request->query('rationale', ''),
            'casualties' => $request->query('casualties', ''),
            'location' => $request->query('location', '')
        ];


        $date_time = Carbon::parse($request->query('date_time', ''));
        $hour = $date_time->format('G');

        if ($hour >= 0 && $hour < 6) {
            $period = "at dawn";
        } elseif ($hour >= 6 && $hour < 12) {
            $period = "in the morning";
        } elseif ($hour >= 12 && $hour < 18) {
            $period = "in the afternoon";
        } else {
            $period = "in the evening";
        }

        $date = $date_time->format('F j, Y');
        $time = $date_time->format('g')." o'clock ".$period;
        
        $pdf = PDF::loadView('Reports.pdf.incident_report', compact('data', 'date', 'time'))->setPaper('letter');
        //return $pdf->download('certificate.pdf');

        return $pdf->stream('certificate.pdf');
    }


    public function DownloadSupplyActivity(Request $request){

        $date_from = $request->query('date_from', '');
        $date_to = $request->query('date_to', '');

        $supply_activities = SupplyActivity::whereBetween('created_at',  [$date_from, $date_to])->get();
        
        $pdf = PDF::loadView('Reports.pdf.supply_activity', compact('date_from', 'date_to', 'supply_activities'))->setPaper('letter');
        //return $pdf->download('certificate.pdf');

        return $pdf->stream('supply_activity-'.$date_from.'-'.$date_to.'.pdf');
    }
    
}
