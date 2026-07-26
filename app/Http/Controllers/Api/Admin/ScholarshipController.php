<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Scholarship\StoreScholarshipRequest;
use App\Http\Requests\Scholarship\UpdateScholarshipRequest;
use App\Http\Resources\ScholarshipDetailResource;
use App\Http\Resources\ScholarshipResource;
use App\Models\Scholarship;
use App\Services\ScholarshipService;
use Illuminate\Http\Request;

class ScholarshipController extends Controller
{
    public function __construct(private ScholarshipService $scholarshipService) {}

    /**
     * GET /admin/scholarships
     * Liste paginée, avec filtre optionnel par statut (brouillon,
     * publié, archivé) — pratique pour l'onglet "Mes brouillons" côté
     * rédacteur par exemple.
     */
    public function index(Request $request)
    {
        $scholarships = Scholarship::query()
            ->with(['country', 'intakes'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->latest()
            ->paginate(20);

        return ScholarshipResource::collection($scholarships);
    }

    public function store(StoreScholarshipRequest $request)
    {
        $scholarship = $this->scholarshipService->create(
            $request->validated(),
            $request->user()->id,
        );

        return new ScholarshipDetailResource($scholarship);
    }

    public function show(Scholarship $scholarship)
    {
        $scholarship->load(['country', 'scholarshipType', 'studyLevels', 'fieldsOfStudy', 'intakes']);

        return new ScholarshipDetailResource($scholarship);
    }

    public function update(UpdateScholarshipRequest $request, Scholarship $scholarship)
    {
        $scholarship = $this->scholarshipService->update($scholarship, $request->validated());

        return new ScholarshipDetailResource($scholarship);
    }

    public function destroy(Scholarship $scholarship)
    {
        $this->scholarshipService->delete($scholarship);

        return response()->json(['message' => 'Bourse supprimée.']);
    }

    /** PATCH /admin/scholarships/{scholarship}/publish */
    public function publish(Scholarship $scholarship)
    {
        return new ScholarshipDetailResource($this->scholarshipService->publish($scholarship));
    }

    /** PATCH /admin/scholarships/{scholarship}/archive */
    public function archive(Scholarship $scholarship)
    {
        return new ScholarshipDetailResource($this->scholarshipService->archive($scholarship));
    }
}