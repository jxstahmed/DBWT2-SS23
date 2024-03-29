<?php

use Illuminate\Support\Facades\Route;
use App\Models\AbArticle;
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

Route::get('/newsite', function () {
    return view('newsite');
});

Route::get('/', function () {
    return view('home');
});


Route::get('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::get('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
Route::get('/isloggedin', [App\Http\Controllers\AuthController::class, 'isloggedin'])->name('haslogin');

Route::get('/articles', [App\Http\Controllers\AbArticlesController::class, 'getSearch']);
Route::post('/articles', [App\Http\Controllers\AbArticlesController::class, 'createArticle']);
Route::get('/newarticle', [App\Http\Controllers\AbArticlesController::class, 'getCreateArticleRouter'])->name("create_article");

Route::get('/impressum', function () {
    return view('impressum');
});
