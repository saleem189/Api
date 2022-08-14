<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class CategoryTransactionController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Category $category)
    {
        $transactions = $category->products()
                        ->whereHas('transactions')
                        ->with('transactions')
                        ->get()
                        ->pluck('transactions')
                        ->collapse();
        return $this->showAll($transactions);
    }

  
}
