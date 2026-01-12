<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Simple implementation: return paginated products as JSON
        return response()->json(Product::query()->paginate(12));
    }
}
