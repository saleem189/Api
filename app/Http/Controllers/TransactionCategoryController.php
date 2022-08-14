<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class TransactionCategoryController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Transaction $transaction)
    {
        $categories = $transaction->products->categories;
        return $this->showAll($categories);
    }

    
}
