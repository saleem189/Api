<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class BuyerTransactionController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        $tranactions = $buyer->transactions;
        return $this->showAll($tranactions);
    }

   
}
