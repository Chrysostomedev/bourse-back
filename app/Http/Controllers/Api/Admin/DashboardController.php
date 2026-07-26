<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\StatsService;

class DashboardController extends Controller
{
    public function __construct(private StatsService $statsService) {}

    /** GET /admin/dashboard */
    public function index()
    {
        return response()->json([
            'data' => $this->statsService->overview(),
        ]);
    }
}