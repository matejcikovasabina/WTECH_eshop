<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AccessoryController;
use App\Http\Controllers\GiftcardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CheckoutController;
use App\Services\CartService;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/knihy', [BookController::class, 'index'])->name('products.index');
Route::get('/doplnky', [AccessoryController::class, 'index'])->name('accessories.index');
Route::get('/darcekove-poukazy', [GiftcardController::class, 'index'])->name('giftcards.index');

Route::get('/produkt/{id}', [ProductController::class, 'show'])->name('products.show');

Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
});

Route::get('/cart/delivery', [CheckoutController::class, 'delivery'])->name('cart.delivery');


Route::post('/cart/delivery', [CheckoutController::class, 'storeDelivery'])
    ->name('cart.delivery.store');

Route::get('/cart/payment', [CheckoutController::class, 'payment'])
    ->name('cart.payment');

Route::post('/cart/payment', [CheckoutController::class, 'storePayment'])
    ->name('cart.payment.store');

Route::get('/cart/summary', [CheckoutController::class, 'summary'])
    ->name('cart.summary');


Route::get('/novinky', [ProductController::class, 'newArrivals'])
    ->name('products.new');


Route::post('/cart/order', [CheckoutController::class, 'placeOrder'])
    ->name('checkout.placeOrder')
    ->middleware('auth');

    Route::get('/clear-cart', function () {
        if (auth()->check()) {
            app(CartService::class)->clearDatabaseCart(auth()->user());
        }

        session()->forget('cart');
        return redirect()->route('cart.index');
    });


// Route::get('/admin', function () {
//     return view('admin.admin-page');
// })->name('admin.index');

// // Admin skupina
// Route::prefix('admin')->name('admin.')->group(function () {
    
//     Route::get('products/delete-page', [ProductController::class, 'deletePage'])->name('products.delete-page');
    
//     Route::resource('products', ProductController::class);
// });

// // Stránka s vyhľadávaním (tvoj Blade)
// Route::get('/admin/delete-product', [ProductController::class, 'deleteSearch'])->name('admin.products.delete_search');

// // Samotná akcia zmazania
// Route::delete('/admin/products/{product}', [ProductController::class, 'destroy'])->name('admin.products.destroy');

// // Zobrazenie edit stránky s vyhľadávaním
// Route::get('/admin/products/edit-search', [ProductController::class, 'editSearch'])->name('admin.products.edit_search');

// // Uloženie zmien (PUT/PATCH)
// Route::put('/admin/products/{product}', [ProductController::class, 'update'])->name('admin.products.update');


// ZAKLADNA PLUCHA ADMINA
Route::get('/admin', function () {
    return view('admin.admin-page');
})->name('admin.index');

// Cela admin skupina pre produkty
Route::prefix('admin/products')->name('admin.products.')->group(function () {
    
    // 1. SPECIALNE STRANKY
    Route::get('/edit-search', [ProductController::class, 'editSearch'])->name('edit_search');
    Route::get('/delete-page', [ProductController::class, 'deletePage'])->name('delete-page');

    // 2. KLASICKE STRAnKY
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/create', [ProductController::class, 'create'])->name('create');
    Route::post('/', [ProductController::class, 'store'])->name('store');

    // 3. STRÁNKY S ID - musia byt posledne
    Route::get('/{product}', [ProductController::class, 'show'])->name('show');
    Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');
    Route::put('/{product}', [ProductController::class, 'update'])->name('update');
    Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
});


require __DIR__.'/settings.php';


