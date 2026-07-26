<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\ScholarshipDetailResource;
use App\Http\Resources\ScholarshipResource;
use App\Services\ScholarshipCatalogService;
use Illuminate\Http\Request;

class ScholarshipController extends Controller
{
    public function __construct(private ScholarshipCatalogService $catalogService) {}

    /**
     * GET /scholarships?q=&country_id=&scholarship_type_id=&study_level_id=&field_of_study_id=
     * Tous les filtres sont optionnels et combinables.
     */
    public function index(Request $request)
    {
        $scholarships = $this->catalogService->search($request->only([
            'q', 'country_id', 'scholarship_type_id', 'study_level_id', 'field_of_study_id',
        ]));

        return ScholarshipResource::collection($scholarships);
    }

    /** GET /scholarships/featured */
    public function featured()
    {
        return ScholarshipResource::collection($this->catalogService->featured());
    }

    /**
     * GET /scholarships/{slug}
     * Le slug (pas l'id) est utilisé dans l'URL — plus lisible pour un
     * lien partagé, et évite d'exposer les ids séquentiels de la table.
     */
    public function show(string $slug)
    {
        $scholarship = $this->catalogService->findBySlugAndRecordView($slug);

        return new ScholarshipDetailResource($scholarship);
    }
}