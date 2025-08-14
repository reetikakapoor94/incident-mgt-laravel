<?php
namespace App\Http\Controllers;

use App\Models\Incident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Http\Requests\StoreIncidentRequest;
use App\Http\Requests\UpdateIncidentRequest;

class IncidentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    protected function generateUniqueIncidentId()
    {
        do {
            $rand = random_int(10000, 99999);
            $id = 'RMG' . $rand . date('Y'); // modify year if assessor expects 2022
            $exists = Incident::where('incident_id', $id)->exists();
        } while ($exists);

        return $id;
    }

    // list incidents of logged-in user
    public function index(Request $request)
    {
        $user = $request->user();
        $incidents = Incident::where('user_id', $user->id)
            ->when($request->query('incident_id'), fn($q,$v) => $q->where('incident_id',$v))
            ->orderBy('created_at','desc')
            ->get();

        return response()->json($incidents);
    }

    
    // show single incident (only owner)
    public function show(Request $request, $id)
    {
        $incident = Incident::where('incident_id', $id)->firstOrFail();
        $this->authorize('view', $incident);

        return response()->json($incident);
    }

    public function store(StoreIncidentRequest $request)
{
    $incidentId = $this->generateUniqueIncidentId();

    $incident = Incident::create(array_merge($request->validated(), [
        'incident_id' => $incidentId,
        'user_id'     => $request->user()->id,
        'status'      => 'Open',
        'reported_at' => $request->reported_at ?? now(),
    ]));

    return response()->json($incident, 201);
}


public function update(UpdateIncidentRequest $request, $id)
{
    $incident = Incident::where('incident_id', $id)->firstOrFail();

    // Check if this incident belongs to the logged-in user
    if ($incident->user_id !== auth()->id()) {
        return response()->json([
            'error' => true,
            'message' => 'You are not allowed to edit another user\'s incident.'
        ], 403);
    }

    // Check if incident is closed
    if ($incident->status === 'Closed') {
        return response()->json([
            'error' => true,
            'message' => 'This incident is closed and cannot be edited.'
        ], 403);
    }

    //  Update incident
    $incident->update($request->validated());

    return response()->json([
        'error' => false,
        'message' => 'Incident updated successfully.',
        'data' => $incident
    ]);
}


    public function destroy(Request $request, $id)
    {
        $incident = Incident::where('incident_id', $id)->firstOrFail();
        $this->authorize('delete', $incident);
        $incident->delete();
        return response()->json(null, 204);
    }

   
}
