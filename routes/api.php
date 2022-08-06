<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\TransactionController;
use App\Models\Seller;
use App\Models\User;
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
 * Aranging routes into group so that all routes bust be authenticates before validation
 */
Route::middleware('auth:sanctum')->group( function () {
    Route::get('/logout', [AuthController::class, 'logout']);

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

Route::get('/test', function(){
    return response()->json([Seller::first()]);
});
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum'); //this line is for only loged in users or who has login Barer code