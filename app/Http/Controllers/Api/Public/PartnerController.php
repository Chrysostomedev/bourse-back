<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\PartnerResource;
use App\Models\Partner;

class PartnerController extends Controller
{
    /** GET /partners — les partenaires mis en avant d'abord */
    public function index()
    {
        $partners = Partner::query()
            ->orderByDesc('is_featured')
            ->orderBy('name')
            ->get();

        return PartnerResource::collection($partners);
    }
}