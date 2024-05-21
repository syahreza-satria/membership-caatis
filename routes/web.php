<?php

use App\Models\Reward;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\HistoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// All Listings
Route::get('/', [RewardController::class, 'index'])->middleware('auth');

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

Route::get('/about', function(){
    return view('about',[
        'banner' => 'TENTANG KAMI'
    ]);
})->middleware('auth');


Route::get('/dashboards', function(){
    return view('dashboards.dashboard');
});