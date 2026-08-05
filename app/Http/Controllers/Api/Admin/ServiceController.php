<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceOffering\ServiceOfferingRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use App\Services\ServiceOfferingService;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function __construct(private ServiceOfferingService $serviceOfferingService) {}

    public function index(Request $request)
    {
        $perPage = (int) ($request->get('per_page') ?? 10);
        $page = (int) ($request->get('page') ?? 1);
        $search = $request->get('q');

        $query = Service::query();

        if ($search) {
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        $services = $query->latest()->paginate($perPage, ['*'], 'page', $page);

        return ServiceResource::collection($services);
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