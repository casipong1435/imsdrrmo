<?php

namespace App\Http\Controllers;

use App\Models\AffectedPerson;
use App\Models\Barangay;
use App\Models\Incident;
use App\Models\User;
use App\Models\IncidentType;
use Illuminate\Http\Request;
use App\Services\SystemNotificationServices;
use Inertia\Inertia;
use Carbon\Carbon;

class TeamController extends Controller
{

    protected $systemNotificationService;

    public function __construct(){
        $this->systemNotificationService = new SystemNotificationServices();
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
        
        return Inertia::render('Team/Pages/Dashboard', compact('barangays', 'incident_types', 'incidents', 'total_incidents', 'date_from' ,'date_to'));
    }

    // END RENDER PAGES

    // POST REQUESTS

        public function createIncidentReport(Request $request)
        {
            
            $request->validate([
                'informant' => 'required',
                'date_time' => 'required',
                'incident_type' => 'required',
                'description' => 'required',
                'casualties' => 'required',
                'barangay' => 'required',
                'purok' => 'required',
            ]);

            try {
                $incident_values = [
                    'informant' => $request->informant,
                    'date_time' => $request->date_time,
                    'incident_type_id' => $request->incident_type,
                    'description' => $request->description,
                    'casualties' => $request->casualties,
                    'barangay_id' => $request->barangay,
                    'purok' => $request->purok,
                    'other_incident' => $request->other_incident
                ];
                $incident = Incident::create($incident_values);

                if (count($request->affectedPersons) > 0) {
                    foreach ($request->affectedPersons as $person) {
                        $affected_person_values = [
                            'name' => $person['name'],
                            'incident_id' => $incident->id,
                            'purok' => $person['purok']
                        ];

                        AffectedPerson::create($affected_person_values);
                    }
                }

                //Notification
                if($request->incident_type != 8){
                    $incident_name = IncidentType::where('id', $request->incident_type)->first(['type'])->type;
                }else{
                    $incident_name = $request->other_incident;
                }
                
                $barangay_name = Barangay::where('id', $request->barangay)->first(['barangay_name'])->barangay_name;
                $time = Carbon::parse($request->date_time)->format('F j, Y g:i A');
                $users = User::whereNot('role', 0)->get();
                $message = "Alert! $incident_name incident had happened in $request->purok, $barangay_name, Tangub City on $time";

                $this->systemNotificationService->sendNotification($users, $message);
                //End Notification
                
                return redirect()->back()->with('success', 'Incident Report Added to the System!');
            } catch (Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }


    // END POST REQUESTS

    // PUT/PATCH REQUEST

        public function updateIncidentReport(Request $request, $id)
        {
            $request->validate([
                'informant' => 'required',
                'date_time' => 'required',
                'incident_type' => 'required',
                'description' => 'required',
                'casualties' => 'required',
                'barangay' => 'required',
                'purok' => 'required',
            ]);

            try {
                $incident_values = [
                    'informant' => $request->informant,
                    'date_time' => $request->date_time,
                    'incident_type_id' => $request->incident_type,
                    'description' => $request->description,
                    'casualties' => $request->casualties,
                    'barangay_id' => $request->barangay,
                    'purok' => $request->purok,
                    'other_incident' => $request->other_incident
                ];

                $incident = Incident::where('id', $id)->update($incident_values);

                if (count($request->affectedPersons) > 0) {
                    foreach ($request->affectedPersons as $person) {
                     
                        if(isset($person['id'])){

                            $affected_person_values = [
                                'name' => $person['name'],
                                'purok' => $person['purok']
                            ];
                            AffectedPerson::where('id', $person['id'])->update($affected_person_values);
                        }else{
                            $affected_person_values = [
                                'name' => $person['name'],
                                'incident_id' => $id,
                                'purok' => $person['purok']
                            ];
    
                            AffectedPerson::create($affected_person_values);
                        }
                    }
                }

                if(count($request->toDeleteAffectedPersonID) > 0){
                    foreach($request->toDeleteAffectedPersonID as $person_id){
                        AffectedPerson::where('id', $person_id)->delete();
                    }
                }
                
                return redirect()->back()->with('success', 'Incident Report Updated !');
            } catch (Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }

        public function editIncidentDesctiption(Request $request){
            
            try{
                $id = $request->input('id');
                $description = $request->input('description');
                Incident::where('id', $id)->update(['description' => $description]);
                return redirect()->back()->with('success', 'Description Updated!');
            }catch(Exception $e){
                return redirect()->back()->with('error', $e->getMessage());
            }
        }

    // ENND PUT/PATCH REQUEST

    // DELETE REQUEST

    // END DELETE REQUEST
}
