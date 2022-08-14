<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BuyerCategoryController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\BuyerProductController;
use App\Http\Controllers\BuyerSellerController;
use App\Http\Controllers\BuyerTransactionController;
use App\Http\Controllers\CategoryBuyerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CategoryProductController;
use App\Http\Controllers\CategorySellerController;
use App\Http\Controllers\CategoryTransactionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\TransactionCategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransactionSellerController;
use App\Http\Controllers\UserController;
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
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

/**
 * Aranging routes into group so that all routes bust be authenticates before validation
 */
Route::middleware('auth:sanctum')->group( function () {
    Route::get('/logout', [AuthController::class, 'logout']);

});

/**
 * Buyer Model Resource Controller
 */
Route::resource('buyer',BuyerController::class)->only(['index', 'show']);
Route::resource('buyer.transactions',BuyerTransactionController::class)->only('index');
Route::resource('buyer.products',BuyerProductController::class)->only('index');
Route::resource('buyer.sellers',BuyerSellerController::class)->only('index');
Route::resource('buyer.category',BuyerCategoryController::class)->only('index');


/**
 * Seller Model Resource Controller
 */
Route::resource('seller',SellerController::class)->only(['index', 'show']);


/**
 * Product Model Resource Controller
 */
Route::resource('product',ProductController::class)->only('index','show');

/**
 * Category Model Resource Controller
 */
Route::resource('category',CategoryController::class)->except('create','edit');
Route::resource('category.buyer',CategoryBuyerController::class)->only('index');
Route::resource('category.sellers',CategorySellerController::class)->only('index');
Route::resource('category.products',CategoryProductController::class)->only('index');
Route::resource('category.transactions',CategoryTransactionController::class)->only('index');


/**
 * User Model Resource Controller 
 */
Route::resource('user',UserController::class);
/**
 * Transaction Model Resource Controller
 */
Route::resource('transaction',TransactionController::class)->only('index','show');
Route::resource('transaction.categories',TransactionCategoryController::class)->only('index');
Route::resource('transaction.seller',TransactionSellerController::class)->only('index');


Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum'); //this line is for only loged in users or who has login Barer code