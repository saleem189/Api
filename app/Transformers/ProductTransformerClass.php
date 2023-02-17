<?php

namespace App\Transformers;

use App\Models\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformerClass extends TransformerAbstract
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
    public function transform(Product $product)
    {
        return [
            'identifier' => (int) $product->id,
            'title' => (string) $product->name,
            'details' => (string) $product->description,
            'stock' => (int) $product->quantity,
            'situation' => (string) $product->status,
            'picture' =>url("images/{$product->image}"),
            'seller' => (int) $product->seller_id,
            'creationDate' => (string) $product->created_at,
            'lastChange' => (string) $product->updated_at,
            'deletedAt' => isset($product->deleted_at) ? (string) $product->deleted_at : null,
            'links' =>[  //by defination HATEOAS is to included in element called links
                [
                    'relation'=>'self',
                    'href'=>route('product.show', $product->id),
                ],
                [
                    'relation'=>'product.buyers',
                    'href'=>route('product.buyers.index', $product->id),
                ],
                [
                    'relation'=>'product.categories',
                    'href'=>route('product.categories.index', $product->id),
                ],
                [
                    'relation'=>'seller', //it showes only information of seller not product
                    'href'=>route('seller.show', $product->seller_id),
                ],
                [
                    'relation'=>'product.transactions',
                    'href'=>route('product.transactions.index', $product->id),
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
            'title' => 'name',
            'details' => 'description',
            'stock' => 'quantity',
            'situation' => 'status',
            'picture' =>'image',
            'seller' => 'seller_id',
            'creationDate' => 'created_at',
            'lastChange' => 'updated_at',
            'deletedAt' => 'deleted_at',

        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }

    /**
     * setting keys for transfomer attributes againts orignal attributes for Posts request in middleware 
     */
    public static function transformedAttributes($index){
        $attributes= [
            'id'=>'identifier',
            'name'=>'title',
            'description'=>'details',
            'quantity'=>'stock' ,
            'status'=>'situation',
            'image'=>'picture',
            'seller_id'=>'seller' ,
            'created_at' =>'creationDate'  ,
            'updated_at' =>'lastChange',
            'deleted_at' =>'deletedAt',

        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
