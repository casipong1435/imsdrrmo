<?php

namespace App\Http\Controllers;

use App\Models\Alerts;
use App\Models\Barangay;
use App\Models\BorrowedSupply;
use App\Models\CertificateRequest;
use App\Models\Incident;
use App\Models\IncidentType;
use App\Models\Log;
use App\Models\Supply;
use App\Models\SupplyActivity;
use App\Models\User;
use App\Services\SMSNotificationService;
use App\Services\SystemNotificationServices;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Hash;

class AdminController extends Controller
{
    protected $smsNotificationService;
    protected $systemNotificationService;
    protected $user_numbers;

    public function __construct()
    {
        $this->smsNotificationService = new SMSNotificationService();
        $this->user_numbers = User::whereNotNull('mobile_number')->pluck('mobile_number')->toArray();
        $this->systemNotificationService = new SystemNotificationServices();
    }

    // MAKE LOG METHOD

    public function createLog($activity, $description)
    {
        Log::create(['activity' => $activity, 'description' => $description]);
    }

    //

    // RENDER PAGE

    public function dashboard(Request $request)
    {
        $year_selected = $request->input('year_selected', Carbon::now()->format('Y'));

        $barangays = Barangay::with([
            'incidents' => function ($query) use ($year_selected) {
                $query
                    ->whereYear('date_time', $year_selected)
                    ->with(['incident_type', 'affected_persons']);  // Load related models inside the filtered incidents
            }
        ])
            ->withCount([
                'incidents' => function ($query) use ($year_selected) {
                    $query->whereYear('date_time', $year_selected);
                }
            ])
            ->orderBy('incidents_count', 'desc')
            ->get();

        $incident_type_id = $request->input('incident_type_id', 6);

        $selected_incident_type = IncidentType::with([
            'incidents' => function ($query) use ($year_selected, $incident_type_id) {
                $query
                    ->where('incident_type_id', $incident_type_id)  // Ensure correct incident type
                    ->whereYear('date_time', $year_selected)
                    ->selectRaw('incident_type_id, MONTH(date_time) as month, COUNT(*) as total')
                    ->groupBy('incident_type_id', 'month')
                    ->orderBy('month');
            }
        ])->find($incident_type_id);

        // Initialize an array for all 12 months (default 0 count)
        $monthlyData = array_fill(1, 12, 0);

        // Fill in actual data
        foreach ($selected_incident_type->incidents as $incident) {
            $monthlyData[$incident->month] = $incident->total;
        }

        $incident_types = IncidentType::withCount(['incidents' => function ($query) use ($year_selected) {
            $query->whereYear('date_time', $year_selected);
        }])->get();

        $monthly_count = array_values($monthlyData);
        $zero_stock = Supply::where('quantity', 0)->count();
        return Inertia::render('Admin/Pages/Dashboard', compact('zero_stock', 'incident_types', 'incident_type_id', 'barangays', 'year_selected', 'selected_incident_type', 'monthly_count'));
    }

    public function account(Request $request)
    {
        $barangays = Barangay::get();

        $role = $request->input('role');
        $users = User::with('barangay')
            ->when(isset($role) ?? null, function ($query) use ($role) {
                $query->where('role', $role);
            })
            ->get();

        $team = User::where('role', 0)->count();
        $admins = User::where('role', 1)->count();
        $resident = User::where('role', 2)->count();

        // dd($users);

        return Inertia::render('Admin/Pages/Account', compact('users', 'role', 'barangays', 'team', 'admins', 'resident'));
    }

    public function certificate()
    {
        $not30daysago = Carbon::now()->subDays(30)->toDateString();

        $certificate_requests = CertificateRequest::whereDate('created_at', '!=', $not30daysago)
            ->orderBy('created_at', 'asc')
            ->get();
        return Inertia::render('Admin/Pages/Certificate', compact('certificate_requests'));
    }

    public function log()
    {
        $activities = Log::paginate(15);
        return Inertia::render('Admin/Pages/Log', compact('activities'));
    }

    // RECORD FOLDER

    public function incident(Request $request)
    {
        $date_from = $request->input('filterDateFrom', Carbon::now()->startOfYear());
        $date_to = $request->input('filterDateTo', Carbon::now());

        $total_incidents = Incident::whereDate('date_time', '>=', $date_from)
            ->whereDate('date_time', '<=', $date_to)
            ->count();

        $incident_types = IncidentType::with(['incidents.barangay', 'incidents.affected_persons', 'incidents' => function ($query) use ($date_from, $date_to) {
            $query
                ->whereDate('date_time', '>=', $date_from)
                ->whereDate('date_time', '<=', $date_to);
        }])->withCount(['incidents as incident_count' => function ($query) use ($date_from, $date_to) {
            $query
                ->whereDate('date_time', '>=', $date_from)
                ->whereDate('date_time', '<=', $date_to);
        }])->get();

        $barangays = Barangay::with(['incidents.incident_type', 'incidents.affected_persons', 'incidents' => function ($query) use ($date_from, $date_to) {
            $query
                ->whereDate('date_time', '>=', $date_from)
                ->whereDate('date_time', '<=', $date_to);
        }])->withCount(['incidents' => function ($query) use ($date_from, $date_to) {
            $query
                ->whereDate('date_time', '>=', $date_from)
                ->whereDate('date_time', '<=', $date_to);
        }])->orderBy('incidents_count', 'desc')->get();
        return Inertia::render('Admin/Pages/Records/Incident', compact('barangays', 'incident_types', 'total_incidents', 'date_from', 'date_to'));
    }

    public function supply(Request $request)
    {
        $status = $request->input('status');
        $type = $request->input('type');
        $borrowed_type = $request->input('borrowed_type');

        $supplies = Supply::when(isset($status) ?? null, function ($query) use ($status) {
            $query->where('status', $status);
        })->when(isset($type) ?? null, function ($query) use ($type) {
            $query->where('type', $type);
        })->get();

        $borrowed_supply = BorrowedSupply::with('supply')->get();

        $supply_activities = SupplyActivity::orderBy('created_at', 'desc')->get();

        return Inertia::render('Admin/Pages/Records/Supply', compact('supplies', 'supply_activities', 'status', 'type', 'borrowed_supply'));
    }

    public function alert(Request $request)
    {
        $barangays = Barangay::get();
        $alerts = Alerts::get();
        return Inertia::render('Admin/Pages/Records/Alert', compact('alerts', 'barangays'));
    }

    // END RECORD FOLDER

    // END RENDER PAGE

    // POST REQUEST
    public function addAccount(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:' . User::class,
            'mobile_number' => 'required|string|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'barangay' => 'required',
            'purok' => 'required',
            'sex' => 'required',
            'role' => 'required|in:0,1,2'
        ]);

        try {
            $user = User::create([
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'username' => $request->username,
                'mobile_number' => $request->mobile_number,
                'sex' => $request->sex,
                'date_of_birth' => $request->date_of_birth,
                'barangay_id' => $request->barangay,
                'purok' => $request->purok,
                'role' => $request->role,
                'password' => Hash::make($request->password),
            ]);
            $this->createLog('Added an account', 'Admin added ' . $request->username . ', a new account to the system.');
            return redirect()->back()->with('success', 'Account Added!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function addItem(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:0,1',
            'description' => 'required',
        ]);

        try {
            $supply = [
                'name' => $request->name,
                'description' => $request->description,
                'unit' => $request->unit,
                'type' => $request->type,
                'status' => $request->status,
                'quantity' => 0,
            ];

            Supply::create($supply);
            $this->createLog('Added an item', 'Admin added ' . $request->name . ' to the system.');
            return redirect()->back()->with('success', 'Item Added!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function sendSMS(Request $request)
    {
        try {
            $request->validate([
                'description' => 'required',
                'selectedBarangay' => 'required|min:1'
            ]);
            $users = User::whereNot('role', 1)->get();
            $this->systemNotificationService->sendNotification($users, $request->description);

            $mobileNumbers = User::whereNot('role', 1)
                ->whereIn('barangay_id', $request->selectedBarangay)
                ->pluck('mobile_number')
                ->filter()  // Removes null, empty, or false values
                ->toArray();
            $this->smsNotificationService->sendSMSAlert($mobileNumbers, $request->description);

            Alerts::create(['description' => $request->description]);
            return redirect()->back()->with('success', 'Alert Sent!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function filterSupplyActivity(Request $request){
        try{
            $date_from = $request->input('date_from', '');
            $date_to = $request->input('date_to', '');

            $supply_activities = SupplyActivity::whereBetween('created_at',  [$date_from, $date_to])->count();

            //dd($supply_activities);

            return response()->json(['count' => $supply_activities]);

        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    // END POST REQUEST

    // PUT/PATCH REQUEST

    public function editAccount(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'mobile_number' => 'required|string|max:255|unique:users,mobile_number,' . $id,
            'barangay' => 'required',
            'purok' => 'required',
            'sex' => 'required',
            'role' => 'required|in:0,1,2'
        ]);

        try {
            $user = User::where('id', $id)->update([
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'username' => $request->username,
                'mobile_number' => $request->mobile_number,
                'sex' => $request->sex,
                'date_of_birth' => $request->date_of_birth,
                'barangay_id' => $request->barangay,
                'purok' => $request->purok,
                'role' => $request->role,
                'password' => Hash::make($request->password),
            ]);
            $this->createLog('Edited an account', 'Admin edited the user account that belongs to ' . $request->username);
            return redirect()->back()->with('success', 'Account Updated!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function changeAccountPassword(Request $request, $id)
    {
        try {
            $request->validate([
                'password' => ['required', 'confirmed', Rules\Password::defaults()]
            ]);

            User::where('id', $id)->update(['password' => Hash::make($request->password)]);

            return redirect()->back()->with('success', 'Password Updated!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function editItem(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:0,1',
            'description' => 'required',
        ]);

        try {
            $supply = [
                'name' => $request->name,
                'description' => $request->description,
                'unit' => $request->unit,
                'type' => $request->type,
                'status' => $request->status,
            ];

            Supply::where('id', $id)->update($supply);
            $this->createLog('Edited an item', 'Admin edited the ' . $request->name);
            return redirect()->back()->with('success', 'Item Updated!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function editItemQuantity(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|min:1'
        ]);

        if ($request->isBorrow) {
            $request->validate([
                'borrower' => 'required'
            ]);
        }

        try {
            $newQuantity = $request->isAdd ? $request->stock + $request->quantity : $request->stock - $request->quantity;

            if ($newQuantity <= 0) {
                $supply = [
                    'quantity' => $newQuantity,
                    'status' => 0
                ];
            } else {
                $supply = [
                    'quantity' => $newQuantity,
                    'status' => 1
                ];
            }

            // dd($newQuantity);

            if ($request->isAdd) {
                SupplyActivity::create(['item_name' => $request->item_name, 'quantity' => '+ ' . $request->quantity]);
            } else {
                if ($request->isBorrow) {
                    BorrowedSupply::create(['supply_id' => $id, 'quantity' => $request->quantity, 'borrower' => $request->borrower]);
                }
                SupplyActivity::create(['item_name' => $request->item_name, 'quantity' => '- ' . $request->quantity]);
            }

            Supply::where('id', $id)->update($supply);
            $message = $request->isAdd ? 'Quantity Added!' : 'Quantity Deducted!';
            return redirect()->back()->with('success', $message);
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function editIncidentDesctiption(Request $request)
    {
        try {
            $id = $request->input('id');
            $description = $request->input('description');
            Incident::where('id', $id)->update(['description' => $description]);
            return redirect()->back()->with('success', 'Description Updated!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function returnItem(Request $request)
    {
        try {
            $request->validate([
                'quantity' => 'required|min:1',
            ]);

            $id = $request->id;
            $leftQuantity = $request->maxQuantity - $request->quantity;
            BorrowedSupply::where('id', $id)->update(['quantity' => $leftQuantity]);
            if ($leftQuantity <= 0) {
                BorrowedSupply::where('id', $id)->delete();
            }

            $lastQuantityOfSuply = Supply::where('id', $request->supply_id)->first(['quantity'])->quantity;

            $lastQuantityOfSuply += $request->quantity;

            Supply::where('id', $request->supply_id)->update(['quantity' => $lastQuantityOfSuply]);

            SupplyActivity::create(['item_name' => $request->name, 'quantity' => "+ $request->quantity"]);

            return redirect()->back()->with('success', "$request->quantity Item Returned");
        } catch (Exeption $e) {
            return redirect()->back()->with('error', $error->getMessage());
        }
    }

    // END PUT/PATCH REQUEST

    // DELETE REQUEST

    public function deleteAccount($id)
    {
        try {
            $username = User::find($id);
            User::where('id', $id)->delete();
            $this->createLog('Removed an Account', 'Admin removed an account ' . $username);
            return redirect()->back()->with('success', 'Account Deleted!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function deleteItem($id)
    {
        try {
            $item_name = Supply::find($id)->name;

            Supply::where('id', $id)->delete();
            $this->createLog('Removed an Item', 'Admin removed an item ' . $item_name . ' from the supply record.');
            return redirect()->back()->with('success', 'Item Deleted!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function deleteAllLog()
    {
        try {
            Log::query()->delete();
            return redirect()->back()->with('success', 'System Logs Cleared!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // END DELETE REQUEST
}
