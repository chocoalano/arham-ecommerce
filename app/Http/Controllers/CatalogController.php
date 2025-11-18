<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function __construct(private ProductRepositoryInterface $products)
    {
        //
    }

    public function index(Request $request)
    {
        $filters = [
            'q' => $request->string('q')->toString(),
            'category' => $request->input('category'),
            'price_min' => $request->input('price_min'),
            'price_max' => $request->input('price_max'),
            'in_stock' => (bool) $request->boolean('in_stock'),
            'has_discount' => (bool) $request->boolean('has_discount'),
            'sort' => $request->input('sort', 'name_asc'),
            'per_page' => (int) $request->input('per_page', 12),
        ];

        $products = $this->products->catalog($filters);
        $categories = $this->products->categoriesWithCounts();
        $priceRange = $this->products->priceRange(['category' => $filters['category']]);

        return view('catalog', [
            'products' => $products,
            'categories' => $categories,
            'filters' => $filters,
            'priceRange' => $priceRange,
        ]);
    }

    public function show(string $slug)
    {
        $product = $this->products->findBySlug($slug);

        if (! $product) {
            abort(404);
        }

        return view('catalog_detail', compact('product'));
    }
}
