<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Book;
use Illuminate\Support\Facades\DB;
use App\Models\Author;

use App\Models\Language;
use App\Models\Publisher;
use App\Models\Binding;
use App\Models\Category;

use Illuminate\Http\Request;



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
        $languages = Language::all();
        $publishers = Publisher::all();
        $bindings = Binding::all();
        $categories = Category::whereNotNull('category_id')->get();
        $authors = Author::all(); // <--- Pridané: načítanie autorov

        return view('admin.admin-add', compact('languages', 'publishers', 'bindings', 'categories', 'authors'));
    }

    /**
     * ADMIN: Ulozenie noveho produktu do databazy
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:40',
            'price' => 'required|numeric',
            'type' => 'required|in:book,giftcard,accessory',
            'category_id' => 'required|exists:categories,id',
            'stock_count' => 'required|integer',
            'description' => 'required|string',
            'images.*' => 'nullable|image|max:2048'
        ]);

        try {
            DB::beginTransaction();

            // 1. Ulozenie do tabulky prducts
            $product = Product::create([
                'name' => $request->name,
                'type' => $request->type,
                'price' => $request->price,
                'stock_count' => $request->stock_count,
                'category_id' => $request->category_id,
                'description' => $request->description,
            ]);

            // 2. podmienene ulozenie do BOOKS alebo GIFTCARDS
            if ($request->type === 'book') {
                $book = Book::create([
                    'product_id' => $product->id,
                    'isbn' => $request->isbn,
                    'publisher_id' => $request->publisher_id,
                    'language_id' => $request->language_id,
                    'binding_id' => $request->binding_id,
                    'year' => $request->year,
                    'weight' => $request->weight,
                    // 'author'       => $request->author,
                    'pages_num'    => $request->pages_num,
                    'width'        => $request->width,
                    'height'       => $request->height,
                    'depth'        => $request->depth,
                ]);

                if ($request->filled('authors_raw')) {
                    $authorsArray = explode(',', $request->authors_raw);
                    $authorIds = [];

                    foreach ($authorsArray as $authorName) {
                        $fullName = trim($authorName);
                        if (empty($fullName)) continue;

                        $parts = explode(' ', $fullName);
                        $lastName = count($parts) > 1 ? array_pop($parts) : $parts[0]; 
                        $firstName = implode(' ', $parts);

                        $author = Author::firstOrCreate([
                            'first_name' => $firstName, 
                            'last_name' => $lastName
                        ]);

                        $authorIds[] = $author->id;
                    }

                    // 3. peepojime knihu s autormi cez tabulku author_book
                    $book->authors()->sync($authorIds);
                }
            } 
            elseif ($request->type === 'giftcard') {
                DB::table('giftcards')->insert([
                    'product_id' => $product->id,
                    'value' => $request->value,
                    'code' => $request->code,
                ]);
            }

            // 3. Ulozenie obrazkov
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    // 1. Vygeneruj unikatny nazov suboru
                    $filename = time() . '_' . $file->getClientOriginalName();
                    
                    // 2. Presun ssbor priamo do public/images/book
                    $file->move(public_path('images/books'), $filename);
                    
                    // 3. Do databazy ulzime cestu ktoru v blade volame
                    $path = 'images/books/' . $filename;
                    $product->images()->create(['image_path' => $path]);
                }
            }

            DB::commit();
            return redirect()->route('admin.products.index')->with('success', 'Produkt pridaný!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();       
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