<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use App\Traits\ApiResponser;
use App\Transformers\ProductTransformerClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SellerProductController extends ApiController
{
    use ApiResponser;

    /**
     * Calling Constructor and parent constructor
     * and assiging middleware to specific function
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('transform.input:' . ProductTransformerClass::class)->only(['store', 'update']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        $products = $seller->products;
        return $this->showAll($products);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $seller)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'quantity' => 'required|integer|min:1',
            'image' => 'required|image'
        ]);

        $data = $request->all();
        $data['status'] = Product::UNAVAILABLE_PRODUCT;
        $data['image'] = $request->image->store('');
        $data['seller_id'] = $seller->id;

        $product = Product::create($data);
        return $this->showOne($product);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function show(Seller $seller)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function edit(Seller $seller)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Seller $seller ,Product $product)
    {
        $request->validate([
            // 'name' => 'required',
            // 'description' => 'required',
            'quantity' => 'integer|min:1',
            'status' =>'in:'.Product::AVAILABLE_PRODUCT.','.Product::UNAVAILABLE_PRODUCT ,
            'image' => 'image'
        ]);
        $this->checkSeller($seller, $product); // function made below and called here

        $product->fill($request->only('name', 'description' , 'quantity'));

        if ($request->has('status')) {
            $product->status = $request->status;

            if ($product->isAvailable() && $product->categories()->count() === 0) {
                return $this->errorResponse('An Active product must have atleast one category ', 409);
            }
        }

        if ($request->hasFile('image')) {
            Storage::delete($product->image);
            $product->image = $request->image->store('');
        }

        if ($product->isClean()) {
            return $this->errorResponse('You need to specify a different value to update', 422);
        }

        $product->save();
        return $this->showOne($product);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seller $seller ,Product $product)
    {
        $this->checkSeller($seller, $product); 
        $product->delete();
        Storage::delete($product->image); //deleting image of current product
        return $this->showOne($product);   
    }

    /**
     * function to check weather seller is the owner of product
     * inorder to update record  
     */
    public function checkSeller($seller , $product)
    {
        if ($seller->id != $product->seller->id) {
            throw new HttpException(422 , 'The specified seller is not the actual seller of the product');
        }
    }
}
