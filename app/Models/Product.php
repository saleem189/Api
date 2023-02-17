<?php

namespace App\Models;

use App\Transformers\ProductTransformerClass;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    const AVAILABLE_PRODUCT = 'available';
    const UNAVAILABLE_PRODUCT = 'unavailable';

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'quantity',
        'status',
        'image',
        'seller_id',

        
    ];

    /**
     * defining/linking Transforers in model 
     */

    public $transformer = ProductTransformerClass::class;


    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        // 'password',
        // 'remember_token',
            'pivot'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // 'email_verified_at' => 'datetime',
    ];

    public function isAvailable(){
        return $this->status == Product::AVAILABLE_PRODUCT;
    }

    // public function isUnAvailable(){
    //     return $this->status == Product::UNAVAILABLE_PRODUCT;
    // }

    public function seller(){
        return $this->belongsTo(Seller::class);
    }

    public function categories(){
        return $this->belongsToMany(Category::class);
    }
    public function transactions(){
        return $this->hasMany(Transaction::class);
    }
}
