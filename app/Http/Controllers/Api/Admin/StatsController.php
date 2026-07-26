<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\StatsService;

class StatsController extends Controller
{
    public function __construct(private StatsService $statsService) {}

    /** GET /admin/stats/by-country */
    public function byCountry()
    {
        return response()->json(['data' => $this->statsService->viewsByCountry()]);
    }

    /** GET /admin/stats/by-field */
    public function byField()
    {
        return response()->json(['data' => $this->statsService->scholarshipsByField()]);
    }
}