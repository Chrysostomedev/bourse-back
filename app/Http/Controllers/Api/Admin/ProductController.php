<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(private ProductService $productService) {}

    public function index(Request $request)
    {
        $perPage = (int) ($request->get('per_page') ?? 10);
        $page = (int) ($request->get('page') ?? 1);
        $search = $request->get('q');

        $query = Product::query();

        if ($search) {
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        $products = $query->latest()->paginate($perPage, ['*'], 'page', $page);

        return ProductResource::collection($products);
    }

    public function store(ProductRequest $request)
    {
        return new ProductResource($this->productService->create($request->validated()));
    }

    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    public function update(ProductRequest $request, Product $product)
    {
        return new ProductResource($this->productService->update($product, $request->validated()));
    }

    public function destroy(Product $product)
    {
        $this->productService->delete($product);

        return response()->json(['message' => 'Produit supprimé.']);
    }
}