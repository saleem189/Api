<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductBuyerTransactionController extends Controller
{
    use ApiResponser;
  
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request ,Product $product, User $buyer)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);
        // return response()->json(['buyer'=>$buyer->id,'buyer_status' => $buyer->verified,'product'=> $product->id, 'status'=> $buyer->isVerified()],200);

        if ($buyer->id == $product->seller_id) {
            return $this->errorResponse('The buyer must be different from the seller', 409);
        }
        if (!$buyer->isVerified()) {
            // if($buyer->verified == 1){
            return $this->errorResponse('The buyer must be a verified user', 409);
        }

        if (!$product->seller->isVerified()) {
            return $this->errorResponse('The seller must be a verified user', 409);
        }

        if (!$product->isAvailable()) {
            return $this->errorResponse('The product is not avaliable', 409);
        }

        if($product->quantity < $request->quantity) {
            return $this->errorResponse('The product does not have enough units for this tranaction', 409);
        }

        return DB::transaction(function() use ($request,$product,$buyer){
            $product->quantity -=$request->quantity;
            $product->save();

            $transaction  = Transaction::create([
                'quantity' => $request->quantity,
                'buyer_id' => $buyer->id,
                'product_id' => $product->id,
            ]);
            return $this->showOne($transaction,201);
        });
    }

}
