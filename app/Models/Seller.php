<?php

namespace App\Models;

use App\Scopes\SellerScope;
use App\Transformers\SellerTransformerClass;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller extends User
{
    use HasFactory;


    /**
     * defining/linking Transforers in model 
     */

    public $transformer = SellerTransformerClass::class;


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new SellerScope);
    }

    public function products(){
        return $this->hasMany(Product::class);
    }
}
