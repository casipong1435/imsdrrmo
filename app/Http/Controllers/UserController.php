<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use App\Models\AffectedPerson;
use App\Models\User;
use App\Models\CertificateRequest;
use App\Services\SystemNotificationServices;
use App\Models\Barangay;
use App\Models\Incident;
use App\Models\IncidentType;

class UserController extends Controller
{
    protected $systemNotificationService;

    public function __construct(){
        $this->systemNotificationService = new SystemNotificationServices();
    }

    public function redirectToLogin(){
        if (auth()->check()){
            switch(auth()->user()->role){
                case 'team':
                        return redirect()->intended(route('team.dashboard', absolute: false));
                    break;
                case 'admin':
                        return redirect()->intended(route('admin.dashboard', absolute: false));
                    break;
                case 'resident':
                        return redirect()->intended(route('user.dashboard', absolute: false));
                    break;
                 
            }  
        }else{
            return redirect()->route('login');
        }

        
    }

    // RENDER PAGES
    public function dashboard(Request $request)
    {
        $date_from = $request->input('filterDateFrom', Carbon::now()->startOfYear());
        $date_to = $request->input('filterDateTo', Carbon::now());

        $total_incidents = Incident::whereDate('date_time', '>=', $date_from)
        ->whereDate('date_time', '<=', $date_to)->count();

        $incident_types = IncidentType::with(['incidents.barangay', 'incidents.affected_persons', 'incidents' => function($query) use($date_from, $date_to){
            $query->whereDate('date_time', '>=', $date_from)
                ->whereDate('date_time', '<=', $date_to);
        }])->withCount(['incidents as incident_count' => function($query) use($date_from, $date_to){
            $query->whereDate('date_time', '>=', $date_from)
                ->whereDate('date_time', '<=', $date_to);
        }])->get();

        $barangays = Barangay::with(['incidents.incident_type', 'incidents.affected_persons', 'incidents' => function($query) use($date_from, $date_to){
            $query->whereDate('date_time', '>=', $date_from)
                ->whereDate('date_time', '<=', $date_to);
        }])->withCount(['incidents' => function($query) use($date_from, $date_to){
            $query->whereDate('date_time', '>=', $date_from)
                ->whereDate('date_time', '<=', $date_to);
        }])->orderBy('incidents_count', 'desc')->get();

        $incidents = Incident::with(['incident_images', 'affected_persons', 'incident_type', 'barangay'])->orderBy('created_at', 'desc')->limit(10)->get();
        
        $fullname = auth()->user()->first_name." ".auth()->user()->last_name; 
        $certificate_requests = CertificateRequest::where('recipient', 'LIKE', '%'.$fullname.'%')->get();
        return Inertia::render('User/Pages/Dashboard', compact('barangays', 'incident_types', 'incidents', 'total_incidents', 'date_from' ,'date_to', 'certificate_requests'));
    }

    public function requestCertificate(Request $request){
        try{
            $has_request = CertificateRequest::where('incident_id', $request->incident_id)->where('recipient', $request->recipient)->count();
            $values = [
                'recipient' => $request->recipient,
                'address' => $request->address,
                'reason' => $request->reason,
                'incident' => $request->incident,
                'incident_id' => $request->incident_id,
                'date' => now(),
            ];

            if ($has_request <= 0){
                CertificateRequest::create($values);
                $admin = User::where('role',1)->get();
                $message = "$request->recipient requested a certification of damage";
                $this->systemNotificationService->sendNotification($admin, $message);
                return redirect()->back()->with('success', "Request Submitted!");
            }

            return redirect()->back()->with('error', "Already Requested!");

        }catch(Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
