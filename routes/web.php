<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Favourite;
use App\Services\ApiService;
use App\Http\Controllers\FavouriteController;
use App\Http\Controllers\ShowPriceController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\CurrencyListController;

Route::get('/', function (ApiService $apiService) {
    $userId = Auth::id();
    $favourites = Favourite::where('user_id', $userId)->get();
    $currenciesData = $apiService->getAllCurrencies();

    return view('home', [
        'favourites' => $favourites,
        'currencies' => $currenciesData['data'] ?? [],
    ]);
})->name('home');

Route::view('/about', 'about');
Route::get('/list', [CurrencyListController::class, 'index'])->name('currencies.list');

Route::get('/show-price', [ShowPriceController::class, 'showPrice'])->name('show.price');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/favourites', [FavouriteController::class, 'showFavourites'])->name('favourites.show');
Route::post('/favourites/add', [FavouriteController::class, 'addOrUpdateFavourite'])->name('favourites.add');
Route::delete('/favourites/delete/{currencyName}', [FavouriteController::class, 'deleteFavourite'])->name('favourites.delete');
