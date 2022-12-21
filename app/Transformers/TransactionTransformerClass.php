<?php

namespace App\Transformers;

use App\Models\Transaction;
use League\Fractal\TransformerAbstract;

class TransactionTransformerClass extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    // protected $defaultIncludes = [
    //     //
    // ];
    
    /**
     * List of resources possible to include
     *
     * @var array
     */
    // protected $availableIncludes = [
    //     //
    // ];
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Transaction $transaction)
    {
        return [
            'identifier' => (int) $transaction->id,
            'quantity' => (int) $transaction->quantity,
            'buyer' => (int) $transaction->buyer_id,
            'product' => (int) $transaction->product_id,
            'creationDate' => (string) $transaction->created_at,
            'lastChange' => (string) $transaction->updated_at,
            'deletedAt' => isset($transaction->deleted_at) ? (string) $transaction->deleted_at : null,
            'links' =>[  //by defination HATEOAS is to included in element called links
                [
                    'relation'=>'self',
                    'href'=>route('transaction.show', $transaction->id),
                ],
                [
                    'relation'=>'transaction.categories',
                    'href'=>route('transaction.categories.index', $transaction->id),
                ],
                [
                    'relation'=>'buyer',//it showes only information of buyer not transaction
                    'href'=>route('buyer.show', $transaction->buyer_id),
                ],
                [
                    'relation'=>'product',//it showes only information of product not transaction
                    'href'=>route('product.show', $transaction->product_id),
                ],

            ]
        ];
    }

    /**
     * seeting keys for transfomer attributes againts orignal attributes for Sorting/Filters 
     */
    public static function orignalAttributes($index){
        $attributes= [
            'identifier' => 'id',
            'quantity' =>'quantity',
            'buyer' => 'buyer_id',
            'product' => 'product_id',
            'creationDate' => 'created_at',
            'lastChange' => 'updated_at',
            'deletedAt' => 'deleted_at',

        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
