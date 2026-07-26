<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /** GET /products?category=ebook|guide_pdf|modele_lettre|autre (optionnel) */
    public function index(Request $request)
    {
        $products = Product::query()
            ->where('is_active', true)
            ->when($request->filled('category'), fn ($q) => $q->where('category', $request->string('category')))
            ->latest()
            ->get();

        return ProductResource::collection($products);
    }

    /** GET /products/{product} */
    public function show(Product $product)
    {
        return new ProductResource($product);
    }
}