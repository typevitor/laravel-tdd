<?php

namespace App\Http\Controllers;

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
}
