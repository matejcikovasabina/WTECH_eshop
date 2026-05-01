<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Book;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
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
        // Nacitaj vsetky produkty z databazy
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
        $authors = Author::all();

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
     * ADMIN: Stranka na potvrdzenie mazania
     */
    public function deletePage(Request $request)
    {
        $query = $request->input('query');
        $product = null;

        if ($query) {
            $product = Product::where('name', 'LIKE', "%{$query}%")->first();
        }

        // Uisti sa, ze tento string 'admin.admin-delete' zodpoveda ceste k suboru
        return view('admin.admin-delete', compact('product'));
    }

    /**
     * ADMIN: Vymazanie produktu
     * Route: DELETE /admin/products/{product}
     */
    public function destroy(Product $product)
    {
        try {
            DB::beginTransaction();

            // 1. Zmazeme fyzicke obrzky subory z disku
            foreach ($product->images as $image) {
                $filePath = public_path($image->image_path);
                if (\File::exists($filePath)) {
                    \File::delete($filePath);
                }
            }

            // 2. zmazeme zanamy o obrazkoch
            $product->images()->delete();

            // 3. Ak je to kniha, zmazeme prepojenia na autorov a knihu samotnu
            if ($product->book) {
                // Odpojime autorov vo vazobnej tabulke (pivot)
                $product->book->authors()->detach();
                // Zmazeme zaznam v tabulke books
                $product->book->delete();
            }

            // 4. Ak je to darcekova poukazka
            if ($product->type === 'giftcard') {
                DB::table('giftcards')->where('product_id', $product->id)->delete();
            }

            // 5. Nakoniec zmazeme hlavny produkt
            $product->delete();

            DB::commit();
            return redirect()->route('admin.products.index')->with('success', 'Produkt bol úspešne zmazaný.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Chyba pri mazaní: ' . $e->getMessage()]);
        }
    }

    public function deleteSearch(Request $request)
    {
        $query = $request->input('query');
        $product = null;

        if ($query) {
            // chceme presnu zhodu alebo podobny nazov
            $product = Product::where('name', 'LIKE', "%{$query}%")->first();
        }

        return view('admin.admin-delete', compact('product'));
    }

    public function editSearch(Request $request)
    {
        $query = $request->input('query');
        $product = null;

        if ($query) {
            $product = Product::where('name', 'LIKE', "%{$query}%")
                ->with(['book.authors', 'giftcard', 'images', 'book.language', 'book.publisher', 'book.binding'])
                ->first();
        }

        // NACITANIE VSETKYCH CISELNIKOV
        $languages = Language::all();
        $publishers = Publisher::all();
        $categories = Category::whereNotNull('category_id')->get();
        $bindings = Binding::all();

        return view('admin.admin-edit', compact(
            'product', 
            'languages', 
            'publishers', 
            'categories',
            'bindings'
        ));
    }

    public function update(Request $request, Product $product)
    {
        // 1. VALIDACIA
        $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'stock_count' => 'required|integer|min:0',
            'description' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'weight'      => 'nullable|numeric',
            'width'       => 'nullable|numeric',
            'height'      => 'nullable|numeric',
            'depth'       => 'nullable|numeric',
            'isbn'        => 'nullable|string|max:20',
        ]);

        try {
            DB::beginTransaction();

            // 2. Aktualizacia základneho produktu
            $product->update([
                'name' => $request->name,
                'price' => $request->price,
                'stock_count' => $request->stock_count,
                'description' => $request->description,
                'category_id' => $request->category_id,
            ]);

            // 3. Ak je to KNIHA
            if ($product->type === 'book' && $product->book) {
                $product->book->update([
                    'isbn' => $request->isbn,
                    'language_id' => $request->language_id,
                    'publisher_id' => $request->publisher_id,
                    'binding_id' => $request->binding_id,
                    'year' => $request->year,
                    'pages_num' => $request->pages_num,
                    'weight' => $request->weight,
                    'width' => $request->width,
                    'height' => $request->height,
                    'depth' => $request->depth,
                ]);

                // Autori - stary sa mazu
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
                    $product->book->authors()->sync($authorIds);
                }
            } 
            // 4. Ak je to DARCEKOVA POUKAZKA
            elseif ($product->type === 'giftcard') {
                DB::table('giftcards')->updateOrInsert(
                    ['product_id' => $product->id],
                    [
                        'value' => $request->value,
                        'code' => $request->code,
                    ]
                );
            }

            // 5. Obrazky
            if ($request->hasFile('images')) {
                foreach ($product->images as $img) {
                    if (File::exists(public_path($img->image_path))) {
                        File::delete(public_path($img->image_path));
                    }
                }
                $product->images()->delete();

                foreach ($request->file('images') as $image) {
                    $imageName = time() . '_' . $image->getClientOriginalName();
                    $image->move(public_path('images/books'), $imageName);
                    $product->images()->create(['image_path' => 'images/books/' . $imageName]);
                }
            }

            DB::commit();
            return redirect()->route('admin.products.edit_search', ['query' => $product->name])
                            ->with('success', 'Produkt bol úspešne upravený.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Chyba pri ukladaní: ' . $e->getMessage()])->withInput();
        }
    }
}