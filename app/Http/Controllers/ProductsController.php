<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        return view('products.index', [
            'products' => Product::paginate(10),
        ]);
    }

    public function create(Request $request)
    {
        return view('products.create');
    }

    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();
        Product::create($data);
        return redirect()->route('products.index');
    }
}
