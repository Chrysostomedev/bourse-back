<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /** GET /services?kind=coaching|formation|dossier (kind optionnel) */
    public function index(Request $request)
    {
        $services = Service::query()
            ->where('is_active', true)
            ->when($request->filled('kind'), fn ($q) => $q->where('kind', $request->string('kind')))
            ->get();

        return ServiceResource::collection($services);
    }
}