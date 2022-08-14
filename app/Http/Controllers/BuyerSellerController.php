<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class BuyerSellerController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        $sellers = $buyer->transactions()->with('products.seller')->get()
                    ->pluck('product.seller')->unique('id')->values(); //this is nested eadger loding .. which means give every product with its seller object
        return $this->showAll($sellers);
    }

  
}
