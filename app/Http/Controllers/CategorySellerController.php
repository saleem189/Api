<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class CategorySellerController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Category $category)
    {
        $sellers = $category->products()
                    ->with('seller')
                    ->get()
                    ->pluck('seller')
                    ->unique()
                    ->values();
        return $this->showAll($sellers);            
    }

   
}
