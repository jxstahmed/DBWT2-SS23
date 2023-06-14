<?php

use App\Http\Controllers\ArticlesAPIController;
use App\Http\Controllers\WebsocketAPIController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/articles', [ArticlesAPIController::class, "getArticles_api"]);
Route::post('/articles', [ArticlesAPIController::class, "createArticles_api"]);
Route::post('/articles/{id}/sold', [ArticlesAPIController::class, "sellArticle_api"]);
Route::post('/articles/{id}/offer', [ArticlesAPIController::class, "offerArticle_api"]);


Route::get('/articles/shoppingcart', [ArticlesAPIController::class, "getCartItems_api"]);
Route::post('/articles/shoppingcart', [ArticlesAPIController::class, "addCartItem_api"]);
Route::post('/articles/shoppingcart/{shoppingcardid}/articles/{articleId}', [ArticlesAPIController::class, "removeCartItem_api"]);

Route::post('/websocket/maintenance', [WebsocketAPIController::class, "enableMaintenance_api"]);
