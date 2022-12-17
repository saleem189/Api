<?php

namespace App\Models;

use App\Scopes\BuyerScope;
use App\Transformers\BuyerTransformerClass;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buyer extends User
{
    use HasFactory ;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * defining/linking Transforers in model 
     */

    public $transformer = BuyerTransformerClass::class;


    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new BuyerScope);
    }

    public function transactions(){
        return $this->hasMany(Transaction::class);
    }
}
