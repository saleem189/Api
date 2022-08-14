<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class SellerTransactionController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        $transactions = $seller->products()
                ->whereHas('transactions')
                ->with('transactions')
                ->get()
                ->pluck('transactions')
                ->collapse()
                ;

        return $this->showAll($transactions);
    }

    
}
