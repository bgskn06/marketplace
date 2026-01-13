<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Show product detail to buyers
     */
    public function show(Product $product)
    {
        $product->load('photos');
        return view('buyer.products.show', compact('product'));
    }
}
