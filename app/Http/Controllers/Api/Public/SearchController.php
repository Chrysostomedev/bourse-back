<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\ScholarshipResource;
use App\Services\ScholarshipCatalogService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __construct(private ScholarshipCatalogService $catalogService) {}

    /**
     * GET /search?q=&country_id=&study_level_id=&field_of_study_id=
     *
     * Volontairement un simple alias de ScholarshipController::index :
     * la logique de filtrage vit une seule fois dans
     * ScholarshipCatalogService::search(). On garde une route /search
     * séparée côté API surtout pour la clarté du contrat front (l'appli
     * a un écran "Recherche" dédié) plutôt que pour une différence de
     * comportement réelle.
     */
    public function index(Request $request)
    {
        $results = $this->catalogService->search($request->only([
            'q', 'country_id', 'scholarship_type_id', 'study_level_id', 'field_of_study_id',
        ]));

        return ScholarshipResource::collection($results);
    }
}