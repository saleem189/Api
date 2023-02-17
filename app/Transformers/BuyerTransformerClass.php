<?php

namespace App\Transformers;

use App\Models\Buyer;
use League\Fractal\TransformerAbstract;

class BuyerTransformerClass extends TransformerAbstract
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
    public function transform(Buyer $buyer)
    {
        return [
            'identifier' => (int) $buyer->id,
            'name' => (string) $buyer->name,
            'email' => (string) $buyer->email,
            'isVerified' => (int) $buyer->verified,
            'creationDate' => (string) $buyer->created_at,
            'lastChange' => (string) $buyer->updated_at,
            'deletedAt' => isset($buyer->deleted_at) ? (string) $buyer->deleted_at : null,
        ];
    }

    /**
     * seeting keys for transfomer attributes againts orignal attributes for Sorting/Filters 
     */
    public static function orignalAttributes($index){
        $attributes= [
            'identifier' => 'id',
            'name' =>'name',
            'email' => 'email',
            'isVerified' => 'verified',
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
            'id' => 'identifier',
            'name' =>'name',
            'email' => 'email',
            'verified' => 'isVerified',
            'created_at' =>'creationDate'  ,
            'updated_at' =>'lastChange',
            'deleted_at' =>'deletedAt',

        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
