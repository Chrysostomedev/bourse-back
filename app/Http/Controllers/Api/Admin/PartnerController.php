<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Partner\PartnerRequest;
use App\Http\Resources\PartnerResource;
use App\Models\Partner;
use App\Services\PartnerService;

class PartnerController extends Controller
{
    public function __construct(private PartnerService $partnerService) {}

    public function index()
    {
        return PartnerResource::collection(Partner::orderBy('name')->get());
    }

    public function store(PartnerRequest $request)
    {
        return new PartnerResource($this->partnerService->create($request->validated()));
    }

    public function show(Partner $partner)
    {
        return new PartnerResource($partner);
    }

    public function update(PartnerRequest $request, Partner $partner)
    {
        return new PartnerResource($this->partnerService->update($partner, $request->validated()));
    }

    public function destroy(Partner $partner)
    {
        $this->partnerService->delete($partner);

        return response()->json(['message' => 'Partenaire supprimé.']);
    }
}