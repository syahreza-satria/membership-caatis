<?php

use App\Models\Reward;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RewardController;

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
Route::post('/rewards/{reward}/redeem', [RewardController::class, 'tukarPoin'])->middleware('auth');

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

Route::get('/about', function(){
    return view('about');
})->middleware('auth');

Route::get('/history', function(){
    return view('history');
})->middleware('auth');

Route::get('/dashboards', function(){
    return view('dashboards.dashboard');
});