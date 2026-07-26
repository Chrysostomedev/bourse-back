<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\ScholarshipResource;
use App\Models\Scholarship;
use App\Services\SavedScholarshipService;
use Illuminate\Http\Request;

class SavedScholarshipController extends Controller
{
    public function __construct(private SavedScholarshipService $savedScholarshipService) {}

    /** GET /me/saved-scholarships */
    public function index(Request $request)
    {
        $scholarships = $request->user()
            ->savedScholarships() // relation belongsToMany définie sur le model User
            ->with(['country', 'intakes'])
            ->latest()
            ->paginate(15);

        return ScholarshipResource::collection($scholarships);
    }

    /**
     * POST + DELETE sur la même action : le toggle() du service gère
     * les deux sens, donc les deux routes pointent ici plutôt que
     * d'avoir deux méthodes quasi identiques.
     */
    public function toggle(Request $request, Scholarship $scholarship)
    {
        $isSaved = $this->savedScholarshipService->toggle($scholarship, $request->user());

        return response()->json(['is_saved' => $isSaved]);
    }
}