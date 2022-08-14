<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class BuyerProductController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        $products = $buyer->transactions()->with('products')->get();
        // $products = $buyer->transactions->products;

        return $this->showAll($products);
    }

  
}
