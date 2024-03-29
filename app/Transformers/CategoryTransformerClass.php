<?php

namespace App\Transformers;

use App\Models\Category;
use League\Fractal\TransformerAbstract;

class CategoryTransformerClass extends TransformerAbstract
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
    public function transform(Category $category)
    {
        return [
            'identifier' => (int) $category->id,
            'title' => (string) $category->name,
            'details' => (string) $category->description,
            'creationDate' => (string) $category->created_at,
            'lastChange' => (string) $category->updated_at,
            'deletedAt' => isset($category->deleted_at) ? (string) $category->deleted_at : null,
            'links' =>[  //by defination HATEOAS is to included in element called links
                [
                    'relation'=>'self',
                    'href'=>route('category.show', $category->id),
                ],
                [
                    'relation'=>'category.buyers',
                    'href'=>route('category.buyer.index', $category->id),
                ],
                [
                    'relation'=>'category.products',
                    'href'=>route('category.products.index', $category->id),
                ],
                [
                    'relation'=>'category.sellers',
                    'href'=>route('category.sellers.index', $category->id),
                ],
                [
                    'relation'=>'category.transactions',
                    'href'=>route('category.transactions.index', $category->id),
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
            'created_at' =>'creationDate'  ,
            'updated_at' =>'lastChange',
            'deleted_at' =>'deletedAt',

        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
