<?php 

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class SellerScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->has('products'); //seller has products .. the product he has put up on sale and buyer buys it form seller by fulfilliung Transaction
    }
}