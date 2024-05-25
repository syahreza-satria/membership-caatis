<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\HistoryController;

// All Listings
Route::get('/', [RewardController::class, 'index'])->name('reward')->middleware('auth');

// Single Listing
Route::get('/rewards/{reward}', [RewardController::class, 'show'])->middleware('auth');

// Tukar Poin
Route::post('/rewards/redeem/{reward}', [RewardController::class, 'redeemPoints'])->middleware('auth');

// Show Register
Route::get('/register', [UserController::class, 'create'])->middleware('guest');

// Create New User
Route::post('/users', [UserController::class, 'store']);

// Log User Out
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth');

// Show Login form
Route::get('/login', [UserController::class, 'login'])->name('login')->middleware('guest');

// Log In User
Route::post('/users/authenticate', [UserController::class, 'authenticate']);

// History Controller
Route::get('/history', [HistoryController::class, 'index'])->middleware('auth');

Route::get('/order', [OrderController::class, 'index'])->middleware('auth');

Route::get('/order/menu', [OrderController::class, 'pembelian'])->name('order.menu')->middleware('auth');

Route::post('/order/create', [OrderController::class, 'createOrder'])->name('order.create')->middleware('auth');

Route::get('/order/verification', [OrderController::class, 'inputKode'])->name('order.inputKode')->middleware('auth');

Route::get('/order/success', [OrderController::class, 'showSuccessPage'])->name('order.success')->middleware('auth');

Route::get('/about', function(){
    return view('about',[
        'banner' => 'TENTANG KAMI'
    ]);
})->middleware('auth');

Route::get('/dashboards', function(){
    return view('dashboards.dashboard');
});
