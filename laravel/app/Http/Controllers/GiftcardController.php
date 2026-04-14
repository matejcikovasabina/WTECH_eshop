<?php

// namespace App\Http\Controllers;

// use App\Models\Product;
// use Illuminate\Http\Request;
// use App\Models\Giftcard;

// class GiftcardController extends Controller
// {
//     // public function index()
//     // {
//     //     $query = Product::where('type', 'giftcard');

//     //     if (request('sort') == 'cheapest') {
//     //         $query->orderBy('price', 'asc');
//     //     } 
//     //     elseif (request('sort') == 'most_expensive') {
//     //         $query->orderBy('price', 'desc');
//     //     } 
//     //     else {
//     //         $query->orderBy('id', 'desc');
//     //     }

//     //     $giftcards = $query->paginate(12);

//     //     return view('giftcards.index', compact('giftcards'));
//     // }


//     // public function show($id)
//     // {
//     //     // 1. Načítame konkrétnu poukážku (tu Laravel automaticky vie, čo je Primary Key)
//     //     $giftcard = \App\Models\Giftcard::findOrFail($id);

//     //     // 2. Načítame odporúčané produkty - ZMENA: 'id' sme prepísali na 'product_id'
//     //     $recommended = \App\Models\Giftcard::where('product_id', '!=', $id) 
//     //                                         ->inRandomOrder()
//     //                                         ->take(6)
//     //                                         ->get();

//     //     // 3. Pošleme do view
//     //     return view('giftcards.show', compact('giftcard', 'recommended'));
//     // }

    
// }


namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Giftcard;
use Illuminate\Http\Request;

class GiftcardController extends Controller
{
    public function index()
    {
        // 1- inicializujeme query
        $query = Product::where('type', 'giftcard')->with('giftcard');

        // 2- filtre a triedenie
        if (request('in_stock')) {
            $query->where('stock_count', '>', 0);
        }

        if (request('sort') == 'cheapest') {
            $query->orderBy('price', 'asc');
        } 
        elseif (request('sort') == 'most_expensive') {
            $query->orderBy('price', 'desc');
        } 
        else {
            $query->orderBy('id', 'desc');
        }

        // 3- Spustíme query
        $products = $query->paginate(12);
        $type = 'giftcard';

        // 4- posleme do view
        $firstProduct = $products->first(); 
        return view('products.index', compact('products', 'type', 'firstProduct'));
    }

    public function show($id)
    {
        $product = Product::with('giftcard')->findOrFail($id);

        $recommended = Product::where('type', 'giftcard')
                                ->where('id', '!=', $id) 
                                ->inRandomOrder()
                                ->take(6)
                                ->get();

        $type = 'giftcard';
        $routeBase = 'giftcards';
        return view('products.show', compact('product', 'recommended', 'type', 'routeBase'));
    }
}