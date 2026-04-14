<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class GiftcardController extends Controller
{
    public function index()
    {
        $query = Product::where('type', 'giftcard');

        if (request('sort') == 'cheapest') {
            $query->orderBy('price', 'asc');
        } 
        elseif (request('sort') == 'most_expensive') {
            $query->orderBy('price', 'desc');
        } 
        else {
            $query->orderBy('id', 'desc');
        }

        $giftcards = $query->paginate(12);

        return view('giftcards.index', compact('giftcards'));
    }
}
