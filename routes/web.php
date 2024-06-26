<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Favourite;
use App\Services\ApiService;
use App\Http\Controllers\FavouriteController;
use App\Http\Controllers\ShowPriceController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\CurrencyListController;
use App\Http\Controllers\ProfileController;

require __DIR__.'/auth.php';

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('home');

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('home');
    } else {
        abort(404);
    }
})->name('home');

Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

Route::post('/logout', function () {
    Auth::logout();
    return redirect()->route('home');
})->middleware('auth')->name('logout');

Route::get('/', function (ApiService $apiService) {
    $userId = Auth::id();
    $favourites = Favourite::where('user_id', $userId)->get();
    $currenciesData = $apiService->getAllCurrencies();

    return view('home', [
        'favourites' => $favourites,
        'currencies' => $currenciesData['data'] ?? [],
    ]);
})->name('home');

Route::get('/select-currencies', [CurrencyController::class, 'showCurrenciesPage'])->name('select.currencies');

Route::view('/about', 'about');
Route::get('/list', [CurrencyListController::class, 'index'])->name('currencies.list');

Route::get('/show-price', [ShowPriceController::class, 'showPrice'])->name('show.price');

Route::middleware('auth')->group(function () {
    Route::get('/favourites', [FavouriteController::class, 'showFavourites']);
    Route::post('/favourites/add', [FavouriteController::class, 'addFavourite']);
    Route::delete('/favourites/delete/{currencyName}', [FavouriteController::class, 'deleteFavourite']);
});