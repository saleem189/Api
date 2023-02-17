<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal;
use League\Fractal\TransformerAbstract;

class UserTransformerClass extends TransformerAbstract
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
    public function transform(User $user)
    {
        return [
            'identifier' => (int) $user->id,
            'name' => (string) $user->name,
            'email' => (string) $user->email,
            'isVerified' => (int) $user->verified,
            'isAdmin' => ($user->admin === true),
            'creationDate' => (string) $user->created_at,
            'lastChange' => (string) $user->updated_at,
            'deletedAt' => isset($user->deleted_at) ? (string) $user->deleted_at : null,

        ];
    }

    /**
     * setting keys for transfomer attributes againts orignal attributes for Sorting/Filters 
     */
    public static function orignalAttributes($index){
        $attributes= [
            'identifier' => 'id',
            'name' =>'name',
            'email' => 'email',
            'isVerified' => 'verified',
            'isAdmin' => 'admin',
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
            'name' => 'name',
            'email' => 'email',
            'verified' => 'isVerified',
            'admin' => 'isAdmin' ,
            'created_at' => 'creationDate',
            'updated_at' => 'lastChange',
            'deleted_at'=> 'deletedAt',

        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
