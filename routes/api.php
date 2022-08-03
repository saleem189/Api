<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/**
 * commeting these default lines
 */
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


/**
 * Buyer Model Resource Controller
 */
Route::resource('buyer',BuyerController::class);


/**
 * Seller Model Resource Controller
 */
Route::resource('seller',SellerController::class);


/**
 * Product Model Resource Controller
 */
Route::resource('product',ProductController::class);

/**
 * Category Model Resource Controller
 */
Route::resource('category',CategoryController::class);

/**
 * Transaction Model Resource Controller
 */
Route::resource('transaction',TransactionController::class);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum'); //this line is fot only loged in usrs or who has login Barer code