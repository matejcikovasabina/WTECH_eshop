<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class AccessoryController extends Controller
{
    public function index()
    {
        $query = Product::where('type', 'accessory')->with('accessory');

        if (request('sort') == 'cheapest') {
            $query->orderBy('price', 'asc');
        } 
        elseif (request('sort') == 'most_expensive') {
            $query->orderBy('price', 'desc');
        } 
        else {
            $query->orderBy('products.id', 'desc');
        }


        $products = $query->paginate(12);
        $type = 'accessory'; 

        return view('products.index', compact('products', 'type'));    
    }
}

