<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class SellerBuyerController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        $buyers = $seller->products()
                ->whereHas('transactions')
                ->with('transactions.buyer')
                ->get()
                ->pluck('transactions')
                ->collapse()
                ->pluck('buyer')
                ->unique('id')
                ->values()
                ;

        return $this->showAll($buyers);        
    }


}
