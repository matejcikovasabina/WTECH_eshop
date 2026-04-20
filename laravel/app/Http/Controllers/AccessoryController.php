<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class AccessoryController extends Controller
{
    // public function index()
    // {
    //     $query = Product::where('type', 'accessory')
    //     ->join('accessories', 'products.id', '=', 'accessories.product_id')
    //     ->select('products.*', 'accessories.description', 'accessories.image_path', 'products.id as product_id');

    // if (request('sort') == 'cheapest') {
    //     $query->orderBy('price', 'asc');
    // } 
    // elseif (request('sort') == 'most_expensive') {
    //     $query->orderBy('price', 'desc');
    // } 
    // else {
    //     $query->orderBy('products.id', 'desc');
    // }

    // $accessories = $query->paginate(12);

    // return view('accessories.index', compact('accessories'));
    
    // }

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

    // public function show($id)
    // {
    //     // Nájdeme produkt, ktorý je typu accessory
    //     $accessory = Product::where('type', 'accessory')
    //         ->join('accessories', 'products.id', '=', 'accessories.product_id')
    //         ->select('products.*', 'accessories.description', 'accessories.image_path')
    //         ->where('products.id', $id)
    //         ->firstOrFail();

    //     // Načítame odporúčané (ostatné doplnky okrem tohto jedného)
    //     $recommended = Product::where('type', 'accessory')
    //         ->join('accessories', 'products.id', '=', 'accessories.product_id')
    //         ->where('products.id', '!=', $id)
    //         ->inRandomOrder()
    //         ->take(6)
    //         ->get();

    //     return view('accessories.show', compact('accessory', 'recommended'));
    // }


}

