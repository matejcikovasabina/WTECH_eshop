<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Book;

class ProductController extends Controller
{
    /**
     * Zobrazenie detailu produktu (frontend)
     */
    public function show($id)
    {
        $product = Product::with([
            'images',
            'category',
            'book.authors',
            'book.language',
            'book.binding',
            'book.publisher',
            'accessory',
            'giftcard',
        ])->findOrFail($id);

        $moreFromAuthor = collect();
        $showAuthorSlider = false;
        $recommended = collect();

        if ($product->book) {
            $authorIds = $product->book->authors->pluck('id');
        
            $moreFromAuthor = Book::with(['product.images', 'authors'])
                ->where('product_id', '!=', $product->id)
                ->whereHas('authors', function ($query) use ($authorIds) {
                    $query->whereIn('authors.id', $authorIds);
                })
                ->inRandomOrder()
                ->take(5)
                ->get();
        
            $showAuthorSlider = $moreFromAuthor->count() >= 5;
        
            $recommended = Book::with(['product.images', 'authors'])
                ->where('product_id', '!=', $product->id)
                ->inRandomOrder()
                ->take(10)
                ->get();
        }

        return view('products.show', compact(
            'product',
            'moreFromAuthor',
            'showAuthorSlider',
            'recommended'
        ));
    }

    /**
     * ADMIN: Zobrazenie zoznamu produktov na editaciu
     */
    public function index()
    {
        // Načítaj všetky produkty z databázy
        $products = Product::with('images', 'book', 'category')
            ->paginate(20);
        
        return view('admin.admin-edit', compact('products'));
    }

    /**
     * ADMIN: Formular na vytvorenie noveho produktu
     */
    public function create()
    {
        return view('admin.admin-add');
    }

    /**
     * ADMIN: Ulozenie noveho produktu do databazy
     */
    public function store(Request $request)
    {
        // Validacia
        $validated = $request->validate([
            'nazov' => 'required|string|max:255',
            'opis' => 'required|string',
            'pocetnasklade' => 'required|integer|min:0',
            'foto1' => 'nullable|image',
            'foto2' => 'nullable|image',
            'foto3' => 'nullable|image',
        ]);

        try {
            // Vytvor produkt
            $product = Product::create([
                'name' => $validated['nazov'],
                'description' => $validated['opis'],
                'stock_quantity' => $validated['pocetnasklade'],
            ]);

            // Ulozenie foto...
            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Produkt ✓ úspešne vytvorený!');
        } 
        catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Chyba pri vytváraní: ' . $e->getMessage());
        }
    }

    /**
     * ADMIN: Zobrazenie formulara na editaciu
     */
    public function edit($id)
    {
        $product = Product::with('images', 'book')->findOrFail($id);
        
        return view('admin.admin-edit', compact('product'));
    }

    /**
     * ADMIN: Ulozenie zmien produktu
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'nazov' => 'required|string|max:255',
            'opis' => 'required|string',
            'pocetnasklade' => 'required|integer|min:0',
        ]);

        try {
            $product->update([
                'name' => $validated['nazov'],
                'description' => $validated['opis'],
                'stock_quantity' => $validated['pocetnasklade'],
            ]);

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Produkt ✓ úspešne aktualizovaný!');
        } 
        catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Chyba pri aktualizácii: ' . $e->getMessage());
        }
    }

    /**
     * ADMIN: Stranka na potvrdzenie mazania
     */
    public function deletePage()
    {
        return view('admin.admin-delete');
    }

    /**
     * ADMIN: Vymazanie produktu
     * Route: DELETE /admin/products/{product}
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        try {
            // Vymaz fotografie
            $product->images()->delete();
            
            // Vymaz produkt
            $product->delete();

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Produkt ✓ úspešne vymazaný!');
        } 
        catch (\Exception $e) {
            return back()
                ->with('error', 'Chyba pri mazaní: ' . $e->getMessage());
        }
    }
}