<?php

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

}