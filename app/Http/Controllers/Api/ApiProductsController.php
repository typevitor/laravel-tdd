<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ApiProductsController extends Controller
{
    public function index(Request $request)
    {
        return Product::all();
    }

    public function create(Request $request)
    {
        return view('products.create');
    }

    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();
        return Product::create($data);
    }
}
