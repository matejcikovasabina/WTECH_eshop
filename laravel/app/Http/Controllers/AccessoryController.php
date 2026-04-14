<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class AccessoryController extends Controller
{
    public function index()
    {
        $query = Product::where('type', 'accessory')
        ->join('accessories', 'products.id', '=', 'accessories.product_id')
        ->select('products.*', 'accessories.description', 'accessories.image_path');

    if (request('sort') == 'cheapest') {
        $query->orderBy('price', 'asc');
    } 
    elseif (request('sort') == 'most_expensive') {
        $query->orderBy('price', 'desc');
    } 
    else {
        $query->latest();
    }

    $accessories = $query->paginate(12);

    return view('accessories.index', compact('accessories'));
    
    }

    
}
