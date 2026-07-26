<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceOffering\ServiceOfferingRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use App\Services\ServiceOfferingService;

class ServiceController extends Controller
{
    public function __construct(private ServiceOfferingService $serviceOfferingService) {}

    public function index()
    {
        return ServiceResource::collection(Service::latest()->get());
    }

    public function store(ServiceOfferingRequest $request)
    {
        return new ServiceResource($this->serviceOfferingService->create($request->validated()));
    }

    public function show(Service $service)
    {
        return new ServiceResource($service);
    }

    public function update(ServiceOfferingRequest $request, Service $service)
    {
        return new ServiceResource($this->serviceOfferingService->update($service, $request->validated()));
    }

    public function destroy(Service $service)
    {
        $this->serviceOfferingService->delete($service);

        return response()->json(['message' => 'Service supprimé.']);
    }
}