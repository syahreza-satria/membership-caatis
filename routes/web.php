<?php

use App\Models\Order;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\DashboardController;

Route::get('/', [OrderController::class, 'showBranch'])->name('showBranch');
Route::get('/order/menu/{outletId}', [OrderController::class, 'pembelian'])->name('order.menu');
Route::post('/order/add-to-cart', [OrderController::class, 'addToCart'])->name('order.addToCart');

// USER LOGIN AND AUTHENTICATION
Route::get('/register', [UserController::class, 'create'])->name('register')->middleware('guest');
Route::post('/users', [UserController::class, 'store'])->name('register.store');
Route::get('/login', [UserController::class, 'login'])->name('login')->middleware('guest');
Route::post('/users/authenticate', [UserController::class, 'authenticate'])->name('login.authenticate');
Route::get('/loginsso', [UserController::class, 'showSsoLoginForm'])->name('showSsoLoginForm')->middleware('guest');
Route::post('/users/sso', [UserController::class, 'authenticateSso'])->name('login.sso');
Route::post('/logout', [UserController::class, 'logout'])->name('logout')->middleware('auth');


Route::middleware('auth')->group(function () {
    // REWARDS STUFF
    Route::get('/reward', [RewardController::class, 'index'])->name('reward');
    Route::get('/rewards/{reward}', [RewardController::class, 'show'])->name('reward.show');
    Route::post('/rewards/redeem/{reward}', [RewardController::class, 'redeemPoints'])->name('reward.redeemPoints');

    // HISTORY
    Route::get('/history', [HistoryController::class, 'index'])->name('history');
    Route::get('/history/rewards', [HistoryController::class, 'rewardHistory'])->name('history.rewards');
    Route::get('/history/orders', [HistoryController::class, 'orderHistory'])->name('history.orders');

    // ORDER
    Route::get('/branch', [OrderController::class, 'showBranch'])->name('showBranch');
    Route::get('/cart/{outletId}', [OrderController::class, 'showCart'])->name('showCart');
    Route::post('/update-cart', [OrderController::class, 'updateCart'])->name('updateCart');
    Route::post('/cart/remove-item', [OrderController::class, 'removeItem'])->name('removeItem');
    Route::post('/order/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::get('/order/verify', [OrderController::class, 'verifyCode'])->name('verifyCode');
    Route::post('/order/confirm', [OrderController::class, 'confirmOrder'])->name('confirmOrder');
    Route::get('/order/receipt', [OrderController::class, 'showReceipt'])->name('showReceipt');
    Route::post('/order/save-basket', [OrderController::class, 'saveBasket'])->name('order.saveBasket');
    Route::post('/log-remove-item', [OrderController::class, 'logRemoveItem'])->name('logRemoveItem');
});

// DASHBOARD
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/verification-codes', [DashboardController::class, 'verificationCodes'])->name('dashboard.verification-codes');
    Route::get('/dashboard/rewards', [DashboardController::class, 'rewards'])->name('dashboard.rewards');
    Route::post('/dashboard/rewards', [DashboardController::class, 'storeReward'])->name('dashboard.rewards.store');
    Route::get('/dashboard/rewards/{reward}/edit', [DashboardController::class, 'editReward'])->name('dashboard.rewards.edit');
    Route::put('/dashboard/rewards/{reward}', [DashboardController::class, 'updateReward'])->name('dashboard.rewards.update');
    Route::post('/dashboard/rewards/{reward}/hide', [DashboardController::class, 'hideReward'])->name('dashboard.rewards.hide');
    Route::post('/dashboard/rewards/{reward}/toggle', [DashboardController::class, 'toggleRewardStatus'])->name('dashboard.rewards.toggle');
    Route::delete('/dashboard/rewards/{reward}', [DashboardController::class, 'destroyReward'])->name('dashboard.rewards.destroy');
    Route::get('/dashboard/users', [DashboardController::class, 'users'])->name('dashboard.users');
    Route::get('/dashboard/orders', [DashboardController::class, 'orders'])->name('dashboard.orders');
    Route::get('/dashboard/orders/search', [DashboardController::class, 'searchOrders'])->name('dashboard.orders.search');

    // Routes for managing branches
    Route::get('/dashboard/branches', [DashboardController::class, 'branches'])->name('dashboard.branches');
    Route::get('/dashboard/branches/create', [DashboardController::class, 'createBranch'])->name('dashboard.branches.create');
    Route::post('/dashboard/branches', [DashboardController::class, 'storeBranch'])->name('dashboard.branches.store');
    Route::get('/dashboard/branches/{branch}/edit', [DashboardController::class, 'editBranch'])->name('dashboard.branches.edit');
    Route::put('/dashboard/branches/{branch}', [DashboardController::class, 'updateBranch'])->name('dashboard.branches.update');
    Route::delete('/dashboard/branches/{branch}', [DashboardController::class, 'destroyBranch'])->name('dashboard.branches.destroy');
});
