<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class BuyerCategoryController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        $categories = $buyer->transactions()->with('products.categories')->get()
                    ->pluck('product.categories')
                    ->collapse()   //convert multi-dimmension array into single dimmension array
                    ->unique('id')
                    ->values(); //this is nested eadger loding .. which means give every product with its categoyy object
        return $this->showAll($categories);
    }


}
