<?php

use App\Models\Order;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\DashboardController;

// USER LOGIN AND AUTHENTICATION
Route::get('/register', [UserController::class, 'create'])->name('register')->middleware('guest');

Route::post('/users', [UserController::class, 'store'])->name('register.store');

Route::get('/login', [UserController::class, 'login'])->name('login')->middleware('guest');

Route::post('/users/authenticate', [UserController::class, 'authenticate'])->name('login.authenticate');

Route::get('/loginsso', [UserController::class, 'showSsoLoginForm'])->name('showSsoLoginForm')->middleware('guest');

Route::post('/users/sso', [UserController::class, 'authenticateSso'])->name('login.sso');

Route::post('/logout', [UserController::class, 'logout'])->name('logout')->middleware('auth');

// REWARDS STUFF
Route::get('/', [RewardController::class, 'index'])->name('reward')->middleware('auth');

Route::get('/rewards/{reward}', [RewardController::class, 'show'])->name('reward.show')->middleware('auth');

Route::post('/rewards/redeem/{reward}', [RewardController::class, 'redeemPoints'])->name('reward.redeemPoints')->middleware('auth');

// HISTORY
Route::get('/history', [HistoryController::class, 'index'])->name('history')->middleware('auth');

Route::get('/history/rewards', [HistoryController::class, 'rewardHistory'])->middleware('auth');

Route::get('/history/orders', [HistoryController::class, 'orderHistory'])->middleware('auth');

// ORDER
Route::get('/order/show-code', [OrderController::class, 'showCode'])->middleware('auth');

Route::get('/order', [OrderController::class, 'index'])->name('order')->middleware('auth');

Route::get('/order/menu', [OrderController::class, 'pembelian'])->name('order.menu')->middleware('auth');

Route::post('/order/create', [OrderController::class, 'createOrder'])->name('order.create')->middleware('auth');

Route::get('/order/verification', [OrderController::class, 'inputKode'])->name('order.inputKode')->middleware('auth');

Route::post('/order/verify-code', [OrderController::class, 'verifyCode'])->name('order.verifyCode')->middleware('auth');

Route::get('/order/success', [OrderController::class, 'showSuccessPage'])->name('order.success')->middleware('auth');

// DASHBOARD
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/verification-codes', [DashboardController::class, 'verificationCodes'])->name('dashboard.verification-codes');
    Route::get('/dashboard/rewards', [DashboardController::class, 'rewards'])->name('dashboard.rewards');
    Route::post('/dashboard/rewards', [DashboardController::class, 'storeReward'])->name('dashboard.rewards.store');
    Route::get('/dashboard/rewards/{reward}/edit', [DashboardController::class, 'editReward'])->name('dashboard.rewards.edit');
    Route::put('/dashboard/rewards/{reward}', [DashboardController::class, 'updateReward'])->name('dashboard.rewards.update');
    Route::delete('/dashboard/rewards/{reward}', [DashboardController::class, 'destroyReward'])->name('dashboard.rewards.destroy');
    Route::get('/dashboard/users', [DashboardController::class, 'users'])->name('dashboard.users');
});

Route::get('/about', function(){
    return view('about',[
        'banner' => 'TENTANG KAMI'
    ]);
})->middleware('auth');

