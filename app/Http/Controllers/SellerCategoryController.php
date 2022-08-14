<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class SellerCategoryController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        $categories = $seller->products()
            ->whereHas('categories')
            ->with('categories')
            ->get()
            ->pluck('categories')
            ->collapse()
            ->unique('id')
            ->values()
            ; 

        return $this->showAll($categories);
    }

   
}
