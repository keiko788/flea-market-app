<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [ItemController::class, 'index'])->name('items.index');
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('items.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');
});

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// プロフィール編集画面
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::middleware(['auth', 'verified', 'profile.registered'])->group(function () {
    // コメント送信
    Route::post('/item/{item_id}/comment', [CommentController::class, 'store'])->name('comments.store');
    // いいね機能
    Route::post('/item/{item_id}/like', [LikeController::class, 'store'])->name('likes.store');
    Route::delete('/item/{item_id}/like', [LikeController::class, 'destroy'])->name('likes.destroy');

    // 商品購入機能
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'show'])->name('purchase.show');
    Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'editAddress'])->name('purchase.edit.address');
    Route::patch('/purchase/address/{item_id}', [PurchaseController::class, 'updateAddress'])->name('purchase.update.address');
    Route::post('/purchase/{item_id}', [PurchaseController::class, 'store'])->name('purchase.store');

    // プロフィール画面
    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage.index');

    // 商品出品機能
    Route::get('/sell', [ItemController::class, 'create'])->name('items.create');
    Route::post('/sell', [ItemController::class, 'store'])->name('items.store');
});
