<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AdminController;


// お問い合わせフォーム
Route::get('/', [ContactController::class, 'index']);
Route::post('/confirm', [ContactController::class, 'confirm']);
Route::get('/confirm', [ContactController::class, 'index']);
Route::post('/thanks', [ContactController::class, 'store']);
Route::get('/thanks', [ContactController::class,'thanks'])->name('thanks');

// 登録画面
Route::get('/register', [RegisterController::class, 'index']);
Route::post('/register', [RegisterController::class, 'store']);

// ミドルウェアを使用する
Route::middleware(['auth'])->group(function () {
// 管理画面で全件表示(初期表示)
Route::get('/admin', [AdminController::class, 'index']);
// 検索結果を表示
Route::get('/search', [AdminController::class, 'index']);
// リセット
Route::get('/reset', [AdminController::class, 'reset']);
// 削除
Route::delete('/delete', [AdminController::class, 'destroy']);});

// エクスポート
Route::get('/export', [AdminController::class, 'export']);