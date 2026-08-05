<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\PartnerResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\ScholarshipResource;
use App\Http\Resources\UserResource;
use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(private DashboardService $service) {}

  public function index(Request $request)
{
    $data = $this->service->getHomeData($request->user());
    
    // Force les Resources même vides pour debug
    return response()->json([
        'user' => $data['user']? new UserResource($data['user']) : null,
        'stats' => $data['stats'],
        'partners' => PartnerResource::collection($data['partners']),
        'featured' => ScholarshipResource::collection($data['featuredScholarships']),
        'recentPosts' => PostResource::collection($data['recentPosts']),
        'pub' => [
            'sponsorName' => 'CI Plus',
            'headline' => 'Rejoignez-nous',
            'subheadline' => 'Faites votre pub ici',
            'phone' => '+225 07 00 51 82 51',
        ]
    ]);
}
}